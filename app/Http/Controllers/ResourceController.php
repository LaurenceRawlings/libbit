<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Project;
use App\Models\Resource;
use Inertia\Inertia;

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
        $project->resources()->create(
            $request->validated() + ['user_id' => auth()->id()]
        );

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
        $resource->update($request->validated());

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

        return redirect()->route('projects.show', $project)->dangerBanner('Resource deleted!');
    }
}
