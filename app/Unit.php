<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    public function products()
    {
        return $this->hasMany('App\Product','unit_id');
    }
}
