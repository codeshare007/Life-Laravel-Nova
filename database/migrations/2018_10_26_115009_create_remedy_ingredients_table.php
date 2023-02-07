<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemedyIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remedy_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->string('ingredientable_type', 100)->nullable();
            $table->unsignedInteger('ingredientable_id')->nullable();

            $table->unsignedInteger('remedy_id');
            $table->foreign('remedy_id')->references('id')->on('remedies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remedy_ingredients');
    }
}
