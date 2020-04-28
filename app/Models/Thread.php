<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Thread extends Model
{
    protected $guarded = [];
    protected $table = 'threads';

    public function posts()
    {
        return $this->hasMany(Post::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
