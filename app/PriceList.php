<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $table = 'price_list';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'product_id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
