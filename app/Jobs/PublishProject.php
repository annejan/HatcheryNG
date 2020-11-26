<?php

namespace App\Jobs;

use App\Events\ProjectUpdated;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phar;
use PharData;

/**
 * Class PublishProject.
 *
 * @author annejan@badge.team
 */
class PublishProject implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    /** @var Project */
    private Project $project;
    /** @var User */
    private User $user;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     * @param User    $user
     *
     * @return void
     */
    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $version = $this->project->getUnpublishedVersion();

        try {
            $filename = 'eggs/'.uniqid($this->project->slug.'_').'.tar';

            $this->makeZip($filename, $version);

            $version->zip = $filename.'.gz';
            $version->size_of_zip = (int) filesize(public_path($version->zip));
            $version->git_commit_id = $this->project->git_commit_id;
            $version->save();

            $this->newVersion($version);

            $this->project->published_at = now();
            $this->project->save();
            event(new ProjectUpdated(
                $version->project,
                'Project '.$version->project->name.' published successfully!'
            ));
        } catch (\Throwable $exception) {
            event(new ProjectUpdated($version->project, $exception->getMessage(), 'danger'));
        }
    }

    /**
     * @param string  $filename
     * @param Version $version
     */
    private function makeZip(string $filename, Version $version): void
    {
        $zip = new PharData(public_path($filename));

        foreach ($version->files as $file) {
            $zip[$this->project->slug.'/'.$file->name] = $file->content;
        }

        $data = [
            'name'        => $this->project->name,
            'description' => $this->project->description,
            'category'    => $this->project->category,
            'author'      => $this->project->user->name,
            'revision'    => $version->revision,
        ];

        if ($this->project->hasValidIcon()) {
            $data['icon'] = 'icon.png';
        }

        $zip[$this->project->slug.'/metadata.json'] = (string) json_encode($data);

        if (!$this->project->dependencies->isEmpty()) {
            $dep = '';
            foreach ($this->project->dependencies as $dependency) {
                $dep .= $dependency->slug."\n";
            }
            $zip[$this->project->slug.'/'.$this->project->slug.'.egg-info/requires.txt'] = $dep;
        }

        if (empty(exec('which minigzip'))) {
            // @codeCoverageIgnoreStart
            $zip->compress(Phar::GZ);
        } else {
            system('minigzip < '.public_path($filename).' > '.public_path($filename.'.gz'));
            // @codeCoverageIgnoreEnd
        }
        unlink(public_path($filename));
    }

    /**
     * @param Version $version
     */
    private function newVersion(Version $version): void
    {
        if ($version->git_commit_id === null) {
            $newVersion = new Version();
            $newVersion->user_id = $this->user->id;
            $newVersion->revision = $version->revision + 1;
            $newVersion->project()->associate($this->project);
            $newVersion->save();
            foreach ($version->files as $file) {
                $newFile = new File();
                $newFile->user_id = $this->user->id;
                $newFile->name = $file->name;
                $newFile->content = $file->content;
                $newFile->version()->associate($newVersion);
                $newFile->save();
            }
        }
    }
}
