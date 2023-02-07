<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantityAndMeasureToIngredientsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('name');
            $table->string('measure')->nullable()->after('name');
            $table->string('quantity')->nullable()->after('name');
        });

        Schema::table('remedy_ingredients', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('name');
            $table->string('measure')->nullable()->after('name');
            $table->string('quantity')->nullable()->after('name');
        });

        Schema::table('supplement_ingredients', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('name');
            $table->string('measure')->nullable()->after('name');
            $table->string('quantity')->nullable()->after('name');
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
            $table->dropColumn([
                'quantity',
                'measure',
                'custom_name',
            ]);
        });

        Schema::table('remedy_ingredients', function (Blueprint $table) {
            $table->dropColumn([
                'quantity',
                'measure',
                'custom_name',
            ]);
        });

        Schema::table('supplement_ingredients', function (Blueprint $table) {
            $table->dropColumn([
                'quantity',
                'measure',
                'custom_name',
            ]);
        });
    }
}
