<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBindedCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binded_cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('card_id')->nullable();
            $table->string('pan', 16)->nullable();
            $table->string('expiry', 4)->nullable();
            $table->string('card_holder')->nullable();
            $table->string('balance')->nullable();
            $table->string('phone', 13)->nullable();
            $table->string('card_token')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('binded_cards');
    }
}
