<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class ProjectsController.
 *
 * @author annejan@badge.team
 */
class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $badge = $this->getBadge($request);
        $category = $this->getCategory($request);
        $search = $this->getSearch($request);

        if ($badge) {
            $projects = $badge->projects()->orderBy('id', 'desc');
            $badge = $badge->slug;
        } else {
            $projects = Project::orderBy('id', 'desc');
        }

        if ($category) {
            $projects = $projects->where('category_id', $category->id);
            $category = $category->slug;
        }

        if ($search) {
            $projects = $projects->where(
                function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                    // @todo perhaps search in README ?
                }
            );
        }

        return view('projects.index')
            ->with(['projects' => $projects->paginate()])
            ->with('badge', $badge)
            ->with('category', $category)
            ->with('search', $search);
    }

    /**
     * Show project content, public method ツ.
     *
     * @param Project $project
     *
     * @return Application|Factory|View
     */
    public function show(Project $project)
    {
        return view('projects.show')
            ->with('project', $project);
    }

    /**
     * @param Request $request
     *
     * @return Badge|null
     */
    private function getBadge(Request $request): ?Badge
    {
        $badge = null;
        if ($request->has('badge') && $request->get('badge')) {
            /** @var Badge|null $badge */
            $badge = Badge::where('slug', $request->get('badge'))->firstOrFail();
        }

        return $badge;
    }

    /**
     * @param Request $request
     *
     * @return Category|null
     */
    private function getCategory(Request $request): ?Category
    {
        $category = null;
        if ($request->has('category') && $request->get('category')) {
            /** @var Category|null $category */
            $category = Category::where('slug', $request->get('category'))->firstOrFail();
        }

        return $category;
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    private function getSearch(Request $request): ?string
    {
        $search = null;
        if ($request->has('search')) {
            $search = $request->get('search');
        }

        return $search;
    }
}
