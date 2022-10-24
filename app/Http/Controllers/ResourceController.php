<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Project;
use App\Models\Resource;
use Inertia\Inertia;
use App\Http\Resources\ResourceResource;
use App\Models\Tag;

class ResourceController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Resource::class, 'resource');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        if (request()->expectsJson()) {
            return ResourceResource::collection($project->resources);
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return Inertia::render('Resources/Edit', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResourceRequest $request, Project $project)
    {
        $validated = $request->validated();

        $resource = $project->resources()->create(
            $validated + ['user_id' => auth()->id()]
        );

        if (isset($validated['tags'])) {
            $tagIds = collect($validated['tags'])->map(function ($tag) {
                return Tag::firstOrCreate(['name' => $tag])->id;
            });

            $resource->tags()->sync($tagIds);
        }

        if (request()->expectsJson()) {
            return new ResourceResource($resource);
        }

        return redirect()->route('projects.show', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Resource $resource)
    {
        if (request()->expectsJson()) {
            return new ResourceResource($resource);
        }

        if ($resource->type == 'link') {
            return redirect($resource->content);
        }

        return Inertia::render('Resources/Show', [
            'project' => $project,
            'resource' => $resource,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Resource $resource)
    {
        return Inertia::render('Resources/Edit', [
            'project' => $project,
            'resource' => $resource,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResourceRequest  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResourceRequest $request, Project $project, Resource $resource)
    {
        $validated = $request->validated();

        $resource->update($validated);

        if (isset($validated['tags'])) {
            $tagIds = collect($validated['tags'])->map(function ($tag) {
                return Tag::firstOrCreate(['name' => $tag])->id;
            });

            $resource->tags()->sync($tagIds);
        } else {
            $resource->tags()->detach();
        }

        if (request()->expectsJson()) {
            return new ResourceResource($resource);
        }

        if ($resource->type == 'link') {
            return redirect()->route('projects.show', $project)->banner('Resource updated!');
        }

        return redirect()->route('projects.resources.show', [$project, $resource])->banner('Resource updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Resource $resource)
    {
        $resource->delete();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('projects.show', $project)->dangerBanner('Resource deleted!');
    }
}
