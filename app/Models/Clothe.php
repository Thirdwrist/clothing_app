<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clothe extends Model
{
    protected $table = 'clothes';

    public const MALE = 'MALE';
    public const FEMALE = 'FEMALE';
    public const OTHER = 'OTHER';
}
