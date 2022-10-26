<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    use Traits\HasUser;
    use Traits\Taggable;
    use Traits\Pinable;

    protected $with = ['tags'];

    protected $fillable = [
        'user_id',
        'name',
        'repo',
    ];

    protected $appends = [
        'is_pinned',
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
