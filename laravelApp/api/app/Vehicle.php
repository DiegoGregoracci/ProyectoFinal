<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'active', 'id_client', 'brand', 'model', 'plate', 'vin',
        'year', 'engine'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function client()
    {
        return $this->belongsTo('App\Client', "id_client");
    }
}
