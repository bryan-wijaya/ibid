<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FirebaseUser extends Model
{
    protected $fillable = [
        'name', 'email','password',
    ];
}
