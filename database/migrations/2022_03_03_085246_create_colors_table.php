<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $data = [
            ['name' => 'red', 'code' => '#DB2828'],
            ['name' => 'orange', 'code' => '#F2711C'],
            ['name' => 'yellow', 'code' => '#FBBD08'],
            ['name' => 'olive', 'code' => '#B5CC18'],
            ['name' => 'green', 'code' => '#21BA45'],
            ['name' => 'teal', 'code' => '#00B5AD'],
            ['name' => 'blue', 'code' => '#2185D0'],
            ['name' => 'violet', 'code' => '#6435C9'],
            ['name' => 'purple', 'code' => '#A333C8'],
            ['name' => 'pink', 'code' => '#E03997']
        ];

        DB::table('colors')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
