<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeRelatedRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_related_recipe', function (Blueprint $table) {
            // Foreign keys
            $table->unsignedInteger('recipe_id');
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->unsignedInteger('related_recipe_id');
            $table->foreign('related_recipe_id')->references('id')->on('recipes')->onDelete('cascade');

            // Constraints
            $table->unique(['recipe_id', 'related_recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_related_recipe');
    }
}
