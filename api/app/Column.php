<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $fillable = [
        'name', 'order_number'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
