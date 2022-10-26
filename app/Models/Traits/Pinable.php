<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait Pinable
{
    public function getIsPinnedAttribute(): bool
    {
        return !is_null($this->pins()->whereUserId(Auth::id())->first());
    }

    public function pins()
    {
        return $this->morphToMany(User::class, 'pinable')->whereDeletedAt(null);
    }
}
