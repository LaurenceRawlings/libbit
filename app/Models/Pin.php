<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pin extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pinables';

    protected $fillable = [
        'user_id',
        'pinable_id',
        'pinable_type',
    ];

    public function resources()
    {
        return $this->morphedByMany(Resource::class, 'pinable');
    }

    public function projects()
    {
        return $this->morphedByMany(Project::class, 'pinable');
    }
}
