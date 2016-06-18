<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
   protected $fillable = [
        'id', 'active', 'lastname', 'name', 'adress', 'telephone','email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

}
