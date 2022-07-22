<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unique_id');
            $table->integer('user_id');
            $table->integer('order_id');
            $table->string('qty');
            $table->string('unit_price');
            $table->date('total');

            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(1);
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
        Schema::dropIfExists('company_invoices');
    }
}
