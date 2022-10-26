<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = [];
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
        }

        if ($request->has('search') || $request->has('tags')) {
            $projects = auth()->user()->currentTeam->projects()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%');
            })
            ->when(count($tags) > 0, function ($query) use ($tags) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('name', $tags);
                });
            })->paginate(9);

            if (request()->expectsJson()) {
                return $projects;
            }
        } else {
            $projects = auth()->user()->currentTeam->projects()->paginate(9);
        }

        if (request()->expectsJson()) {
            return ProjectResource::collection(auth()->user()->currentTeam->projects);
        }

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'search' => $request->search,
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Projects/Edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $project = auth()->user()->currentTeam->projects()->create(
            $validated + ['user_id' => auth()->id()]
        );

        if (isset($validated['tags'])) {
            $tagIds = collect($validated['tags'])->map(function ($tag) {
                return Tag::firstOrCreate(['name' => $tag])->id;
            });

            $project->tags()->sync($tagIds);
        }

        if ($request->expectsJson()) {
            return new ProjectResource($project);
        }

        return redirect()->route('projects.show', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        if (request()->expectsJson()) {
            return new ProjectResource($project);
        }

        return Inertia::render('Projects/Show', [
            'project' => $project,
            'resources' => $project->resources()->paginate(9),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        $project->update($validated);

        if (isset($validated['tags'])) {
            $tagIds = collect($validated['tags'])->map(function ($tag) {
                return Tag::firstOrCreate(['name' => $tag])->id;
            });

            $project->tags()->sync($tagIds);
        } else {
            $project->tags()->detach();
        }

        if ($request->expectsJson()) {
            return new ProjectResource($project);
        }

        return redirect()->route('projects.show', $project)->banner('Project updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('projects.index')->dangerBanner('Project deleted!');
    }
}
