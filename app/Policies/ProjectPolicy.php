<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project)
    {
        return $user->belongsToTeam($project->team)
            ? Response::allow()
            : Response::deny('Your team does not own this project.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasTeamPermission($user->currentTeam, 'create')
            ? Response::allow()
            : Response::deny('You do not have permissions to create a project.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Project $project)
    {
        if ($project->belongsToUser($user)) {
            return Response::allow();
        }

        if (!$user->belongsToTeam($project->team)) {
            return Response::deny('Your team does not own this project.');
        }

        return $user->hasTeamPermission($user->currentTeam, 'update')
            ? Response::allow()
            : Response::deny('You do not have permissions to update a project.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Project $project)
    {
        if ($project->belongsToUser($user)) {
            return Response::allow();
        }

        if (!$user->belongsToTeam($project->team)) {
            return Response::deny('Your team does not own this project.');
        }

        return $user->hasTeamPermission($user->currentTeam, 'delete')
            ? Response::allow()
            : Response::deny('You do not have permissions to delete a project.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project)
    {
        if (!$user->belongsToTeam($project->team)) {
            return Response::deny('Your team does not own this project.');
        }

        return $this->create($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project)
    {
        if (!$user->belongsToTeam($project->team)) {
            return Response::deny('Your team does not own this project.');
        }

        return $this->delete($user, $project);
    }
}
