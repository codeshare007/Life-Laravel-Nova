<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToIngredientsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('remedy_ingredients', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('remedy_ingredients', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
