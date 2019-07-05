<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'title', 'sprint_id'
    ];

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }
}
