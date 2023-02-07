<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplementIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplement_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->string('ingredientable_type', 100)->nullable();
            $table->unsignedInteger('ingredientable_id')->nullable();

            $table->unsignedInteger('supplement_id');
            $table->foreign('supplement_id')->references('id')->on('supplements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplement_ingredients');
    }
}
