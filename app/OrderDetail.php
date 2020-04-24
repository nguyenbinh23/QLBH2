<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_details";

    protected $fillable = [
        'product_id', 'order_id', 'product_name' , 'product_code' , 'quantity' , 'unit' , 'price' , 'discount' , 'totalprice', 'image'
    ];
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
