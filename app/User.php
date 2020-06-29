<?php

namespace App;

use App\Models\Collection;
use App\Models\Thread;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const USER = 'user';
    const BUSINESS = 'business';
    const DESIGNER = 'designer';

    const MALE = 'male';
    const FEMALE = 'female';

    protected $guarded = [];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function savedThreads()
    {
        return $this->belongsToMany(Thread::class, 'saved_models', 'user_id', 'model_id')->where('model_type', Thread::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
