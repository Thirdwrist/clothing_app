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

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'model_has_tags',
            'model_id',
            'tag_id'
        )->wherePivot(
            'model_type',
            self::class
        )->withTimestamps()
            ->withPivot(['model_type', 'id']);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'collections_has_threads');
    }
}
