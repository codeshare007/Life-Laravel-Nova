<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemediesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remedies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 100);
            $table->string('image_url')->nullable();
            $table->string('color', 6)->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remedies');
    }
}
