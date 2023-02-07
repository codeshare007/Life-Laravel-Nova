<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oils', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('name');
            $table->text('image_url');
            $table->string('color', 6);
            $table->text('latin_name');
            $table->text('emotion_1');
            $table->text('emotion_2');
            $table->text('emotion_3');
            $table->longText('fact')->nullable();
            $table->longText('research');
            $table->boolean('is_featured')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oils');
    }
}
