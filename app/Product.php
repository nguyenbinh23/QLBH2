<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'quantity' , 'unit' , 'category_id' , 'description'
    ];
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function pricelist()
    {
        return $this->hasMany('App\PriceList');
    }
    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }
    public function order_details(){
        return $this->hasMany('App\OrderDetails','product_id');
    }
}
