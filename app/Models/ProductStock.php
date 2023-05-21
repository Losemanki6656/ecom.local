<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'variant', 'sku', 'price', 'qty', 'image'];
    //
    public function product(){
    	return $this->belongsTo(Product::class);
    }

    public function wholesalePrices() {
        return $this->hasMany(WholesalePrice::class);
    }

    public function getPriceCurrency()
    {
        $product = Product::find($this->product_id);

        if(!$product->currency){
            return $this->price;
        }

        $currency = Currency::find($product->currency);
        $currencyUZS = Currency::find(29);

        return ($this->price / $currency->exchange_rate) * $currencyUZS->exchange_rate;
    }
}
