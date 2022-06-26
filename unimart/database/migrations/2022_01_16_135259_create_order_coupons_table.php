<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code',10)->unique();
            $table->unsignedInteger('qty');
            $table->tinyInteger('condition');
            $table->unsignedInteger('value');
            $table->tinyInteger('status');
            $table->dateTime('date_end');
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');


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
        Schema::dropIfExists('order_coupons');
    }
}
