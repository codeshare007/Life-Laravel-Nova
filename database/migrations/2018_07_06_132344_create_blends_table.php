<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blends', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 100);
            $table->text('image_url');
            $table->string('color', 6);
            $table->text('emotion_1');
            $table->text('emotion_2');
            $table->text('emotion_3');
            $table->longText('fact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blends');
    }
}
