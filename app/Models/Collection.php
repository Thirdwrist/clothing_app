<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    protected $guarded = [];

    protected $table = 'collections';

    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'collections_has_threads')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
