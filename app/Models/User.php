<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'github_id', 'github_token', 'github_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'github_token',
        'github_refresh_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'has_password',
    ];

    public function getHasPasswordAttribute()
    {
        return $this->password !== null;
    }

    public function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=16a34a&background=86efac';
    }

    public function pinned()
    {
        return $this->morphMany(Pin::class, 'user');
    }

    public function pinnedResources()
    {
        return $this->currentTeam->resources()->whereHas('pins', function ($query) {
            $query->whereUserId($this->id)->whereDeletedAt(null);
        });
    }

    public function pinnedProjects()
    {
        return $this->currentTeam->projects()->whereHas('pins', function ($query) {
            $query->whereUserId($this->id)->whereDeletedAt(null);
        });
    }
}
