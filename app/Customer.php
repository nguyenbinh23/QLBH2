<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";

    protected $fillable = [
        'name', 'email', 'address' , 'phone' , 'sorting' , 'tax_code',
    ];

    public function orders()
    {
        return $this->hasMany('App\Order','customer_id');
    }
}
