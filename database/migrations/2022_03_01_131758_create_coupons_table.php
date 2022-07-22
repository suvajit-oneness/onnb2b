<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('coupon_code');
            $table->tinyInteger('type')->comment('1: flat, 2: offer')->default(1);
            $table->double('amount', 8, 2);
            $table->bigInteger('max_time_of_use');
            $table->bigInteger('max_time_one_can_use');
            $table->bigInteger('no_of_usage')->default(0);
            $table->string('start_date');
            $table->string('end_date');
            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
