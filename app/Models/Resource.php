<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    use Traits\HasUser;
    use Traits\Taggable;
    use Traits\Pinable;

    protected $with = ['tags'];

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'link',
        'content',
    ];

    protected $appends = [
        'is_pinned',
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
