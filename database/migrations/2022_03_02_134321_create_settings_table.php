<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_heading');
            $table->longText('content')->nullable();
            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $data = [
            ['page_heading' => 'about'],
            ['page_heading' => 'terms'],
            ['page_heading' => 'privacy'],
            ['page_heading' => 'cancellation_and_refund'],
            ['page_heading' => 'return'],
            ['page_heading' => 'address'],
            ['page_heading' => 'contact_no'],
            ['page_heading' => 'email_id'],
            ['page_heading' => 'google_map_iframe'],
            ['page_heading' => 'fb_link'],
            ['page_heading' => 'twitter_link'],
            ['page_heading' => 'youtube_link'],
            ['page_heading' => 'insta_link'],
        ];

        DB::table('settings')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
