<?php

namespace App\Models\Traits;

use App\Models\User;

trait HasUser
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function belongsToUser($user): bool
    {
        if ($user instanceof User) {
            return $this->user_id === $user->id;
        }

        return $this->user_id === $user;
    }
}
