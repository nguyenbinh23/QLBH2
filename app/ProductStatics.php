<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStatics extends Model
{
    protected $table="product_statics";
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id' , 'sell_quantity' , 'buy_quantity'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
