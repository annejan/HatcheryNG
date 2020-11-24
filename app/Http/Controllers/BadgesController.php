<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class BadgesController.
 *
 * @author annejan@badge.team
 */
class BadgesController extends Controller
{
    /**
     * Show badges.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('badges.index')
            ->with('badges', Badge::paginate());
    }

    /**
     * Show badge, public method ツ.
     *
     * @param Badge $badge
     *
     * @return Application|Factory|View
     */
    public function show(Badge $badge)
    {
        return view('badges.show')
            ->with('badge', $badge)
            ->with('projects', $badge->projects()->paginate());
    }
}
