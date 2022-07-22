<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCouponUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('coupon_code_id');
            $table->string('coupon_code', 100);
            $table->double('discount', 10, 2);
            $table->double('total_checkout_amount', 10, 2);
            $table->double('final_amount', 10, 2);
            $table->bigInteger('user_id')->default(0);
            $table->string('email');
            $table->string('ip');
            $table->bigInteger('order_id');
            $table->string('usage_time');
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
        Schema::dropIfExists('coupon_usages');
    }
}
