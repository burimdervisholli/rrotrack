<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'estimate', 'user_id', 'role_id', 'column_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Taskrole::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}
