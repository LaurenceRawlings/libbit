<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    use Traits\HasUser;
    use Traits\Taggable;

    protected $with = ['tags'];

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'link',
        'content',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function team()
    {
        return $this->project->team;
    }
}
