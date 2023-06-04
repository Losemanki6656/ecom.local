<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Product extends Model
{

    protected $guarded = ['choice_attributes'];

    protected $with = ['product_translations', 'taxes'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function getPriceCurrency()
    {
        if(!$this->currency){
            return $this->unit_price;
        }

        $currency = Currency::find($this->currency);
        $currencyUZS = Currency::find(29);

        return ($this->unit_price / $currency->exchange_rate) * $currencyUZS->exchange_rate;
    }

    public function getProductDiscountAmount()
    {
        if(!$this->currency){
            $currency = 29;
        }

        $currency = Currency::find($currency);
        $currencyUZS = Currency::find(29);
        
        if(session('currency_code') == "USD")     
            return (($this->discount / $currency->exchange_rate) * $currencyUZS->exchange_rate);
        else 
            return (int)(($this->discount / $currency->exchange_rate) * $currencyUZS->exchange_rate);
    }

    public function getDiscountedAmount(){

        $currency = Currency::where('id','=',self::UZS)->first();
        
        $exchange_rate = $currency->exchange_rate;
        return $this->discount * $exchange_rate;
    }


    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }

    public function scopeDigital($query)
    {
        return $query->where('digital', 1);
    }
}
