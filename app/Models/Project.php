<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    use Traits\HasUser;
    use Traits\Taggable;

    protected $fillable = [
        'user_id',
        'name',
        'repo',
    ];

    protected $withCount = [
        'resources',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
