<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'customer_id', 'kind', 'name' , 'address' , 'phone' , 'discount', 'tax_code' , 'tax' , 'total_price'
    ];

    public function order_details()
    {
        return $this->hasMany('App\OrderDetail','order_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer',);
    }
}
