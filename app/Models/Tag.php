<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function threads()
    {
        return $this->belongsToMany(
            Thread::class,
            'model_has_tags',
            'tag_id',
            'model_id'
        )->where(
            'model_type',
            Thread::class
        );
    }

    public function collections()
    {
        return $this->belongsToMany(
            Collection::class,
            'model_has_tags',
            'tag_id',
            'model_id'
        )->where(
            'model_type',
            Collection::class
        );
    }
}
