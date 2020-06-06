<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SavedModel extends Model
{
    protected $guarded = [];

    public function model()
    {
        if($this->model_type === Thread::class)
        {
            return $this->belongsTo(Thread::class, 'model_id');
        }

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
