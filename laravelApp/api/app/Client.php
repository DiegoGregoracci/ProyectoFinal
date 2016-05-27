<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
   protected $fillable = [
        'id', 'active', 'id_user', 'lastname', 'name', 'adress', 'tel1',
        'tel2', 'email', 'cuit', 'comments'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Vehicle');
    }
}
