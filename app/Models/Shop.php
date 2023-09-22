<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Shop extends Model
    {

        protected $with = ['user'];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function example_comission()
        {
            return $this->belongsTo(ExampleComission::class, 'example_comission_id');
        }

        public function seller_package()
        {
            return $this->belongsTo(SellerPackage::class);
        }

        public function followers()
        {
            return $this->hasMany(FollowSeller::class);
        }

        public function details()
        {
            return $this->hasOne(ShopDetail::class);
        }
    }
