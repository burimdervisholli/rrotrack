<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'description', 'deadline'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }
}
