<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            // Foreign keys
            $table->string('favouriteable_type', 100);
            $table->unsignedInteger('favouriteable_id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['favouriteable_id', 'favouriteable_type']);

            // Constraints
            $table->unique(['favouriteable_id', 'favouriteable_type', 'user_id'], 'unique_favourite');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favourites');
    }
}
