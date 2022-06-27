<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Users extends Model 
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'name', 'email', 'password',
    ];
}
