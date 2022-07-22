<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartEndDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('start_end_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('start_location');
            $table->string('end_location');
            $table->double('start_lat');
            $table->double('end_lat');
            $table->double('start_lng');
            $table->double('end_lng');
            $table->date('start_date');
            $table->date('end_date');
            $table->datetime('start_time');
            $table->datetime('end_time');
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
        Schema::dropIfExists('start_end_days');
    }
}
