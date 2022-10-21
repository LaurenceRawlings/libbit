<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ResourcePolicy
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
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Resource $resource)
    {
        return $user->belongsToTeam($resource->team())
            ? Response::allow()
            : Response::deny('Your team does not own this resource.');
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
            : Response::deny('You do not have permissions to create a resource.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Resource $resource)
    {
        if ($resource->belongsToUser($user)) {
            return Response::allow();
        }

        if (!$user->belongsToTeam($resource->team())) {
            return Response::deny('Your team does not own this resource.');
        }

        return $user->hasTeamPermission($user->currentTeam, 'update')
            ? Response::allow()
            : Response::deny('You do not have permissions to update a resource.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Resource $resource)
    {
        if ($resource->belongsToUser($user)) {
            return Response::allow();
        }

        if (!$user->belongsToTeam($resource->team())) {
            return Response::deny('Your team does not own this resource.');
        }

        return $user->hasTeamPermission($user->currentTeam, 'delete')
            ? Response::allow()
            : Response::deny('You do not have permissions to delete a resource.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Resource $resource)
    {
        if (!$user->belongsToTeam($resource->team())) {
            return Response::deny('Your team does not own this resource.');
        }

        return $this->create($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Resource $resource)
    {
        if (!$user->belongsToTeam($resource->team())) {
            return Response::deny('Your team does not own this resource.');
        }

        return $this->delete($user, $resource);
    }
}
