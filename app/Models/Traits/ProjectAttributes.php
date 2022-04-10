<?php

namespace App\Models\Traits;

use App\Models\Category;
use App\Models\File;
use App\Models\User;
use App\Models\Version;
use App\Support\Helpers;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Trait ProjectAttributes.
 *
 * @package App\Models\Traits
 * @author annejan@badge.team
 */
trait ProjectAttributes {
    abstract function versions(): HasMany;
    abstract function votes(): HasMany;
    abstract function category(): BelongsTo;
    abstract function states(): HasMany;

    /**
     * Forbidden names for apps.
     *
     * @var array<string>
     */
    public static $forbidden = [
        'os', 'uos', 'badge', 'esp32', 'ussl', 'time', 'utime', 'splash', 'launcher', 'installer', 'ota_update',
        'boot', 'appglue', 'database', 'dialogs', 'deepsleep', 'magic', 'ntp', 'rtcmem', 'machine', 'setup', 'version',
        'wifi', 'woezel', 'network', 'socket', 'uhashlib', 'hashlib', 'ugfx', 'btree', 'request', 'urequest', 'uzlib',
        'zlib', 'ssl', 'create', 'delete', 'system',
    ];

    /**
     * @return int
     */
    public function getSizeOfZipAttribute(): ?int
    {
        $version = $this->versions()->published()->get()->last();

        return $version === null ? null : (int) $version->size_of_zip;
    }

    /**
     * @return int
     */
    public function getSizeOfContentAttribute(): ?int
    {
        $version = $this->versions()->published()->get()->last();
        if ($version === null) {
            $version = $this->versions->last();
        }
        /** @var Version $version */
        $size = 0;
        foreach ($version->files as $file) {
            $size += strlen($file->content);
        }

        return $size;
    }

    /**
     * @return string
     */
    public function getSizeOfContentFormattedAttribute(): string
    {
        return Helpers::formatBytes((int) $this->getSizeOfContentAttribute());
    }

    /**
     * @return string
     */
    public function getSizeOfZipFormattedAttribute(): string
    {
        return Helpers::formatBytes((int) $this->getSizeOfZipAttribute());
    }

    /**
     * @return string
     */
    public function getCategoryAttribute(): ?string
    {
        if ($this->category()->first() === null) {
            return 'uncategorised';
	}
	/** @var Category $category */
	$category = $this->category()->first();
	return $category->slug;
    }

    /**
     * @return string|null
     */
    public function getDescriptionAttribute(): ?string
    {
        $full = true;
        $request = request();
        if ($request->has('description') && $request->description === false) {
            $full = false;
        }

        /** @var Version|null $version */
        $version = $this->versions->last();
        if ($version && $version->files()->where('name', 'like', 'README.md')->count() === 1) {
            /** @var File $file */
            $file = $version->files()->where('name', 'like', 'README.md')->first();

            if ($full) {
                return $file->content;
            } else {
                Str::limit((string) $file->content, 16);
            }
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getDescriptionHtmlAttribute(): ?string
    {
        if ($this->description) {
            return Markdown::parse($this->description);
        }

        return null;
    }

    /**
     * @return bool|null
     */
    public function userVoted(): ?bool
    {
        /** @var User|null $user */
        $user = Auth::guard()->user();
        if ($user === null) {
            return null;
        }

        return $this->votes()->where('user_id', $user->id)->exists();
    }

    /**
     * @return string
     */
    public function getStatusAttribute(): string
    {
        foreach (['working', 'in_progress', 'broken'] as $status) {
            if ($this->states()->where('status', $status)->exists()) {
                return $status;
            }
        }

        return 'unknown';
    }

    /**
     * @return bool
     */
    public function hasValidIcon(): bool
    {
        /** @var Version $version */
        $version = $this->versions->last();
        /** @var File|null $file */
        $file = $version->files()->where('name', 'icon.png')->get()->last();
        if ($file === null) {
            return false;
        }

        return $file->isValidIcon();
    }

    /**
     * @return float
     */
    public function getScoreAttribute(): float
    {
        if ($this->votes === null || $this->votes->count() === 0) {
            return 0;
        }
        $score = 0;
        foreach ($this->votes as $vote) {
            if ($vote->type === 'up') {
                $score++;
            }
            if ($vote->type === 'down') {
                $score--;
            }
        }

        return $score / $this->votes->count();
    }

    /**
     * @return string
     */
    public function getAuthorAttribute(): string
    {
        if (empty($this->user->name)) {
            return 'Unknown';
        } else {
            return $this->user->name;
        }
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public static function isForbidden(string $slug): bool
    {
        return in_array($slug, self::$forbidden);
    }
}
