<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Inertia\Inertia;
use App\Models\Tag;

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
    public function index()
    {
        if (request()->expectsJson()) {
            return ProjectResource::collection(auth()->user()->currentTeam->projects);
        }

        return Inertia::render('Projects/Index', [
            'projects' => auth()->user()->currentTeam->projects,
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
            'resources' => $project->resources,
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
