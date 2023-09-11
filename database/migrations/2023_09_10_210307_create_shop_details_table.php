<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned()->index()->nullable();
            $table->string('name')->nullable();
            $table->string('director')->nullable();
            $table->string('shkli')->nullable();
            $table->string('inn')->nullable();
            $table->string('bank')->nullable();
            $table->string('mfo')->nullable();
            $table->string('b_number')->nullable();
            $table->string('d_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_details');
    }
}
