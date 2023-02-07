<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToSupplementIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplement_ingredients', function (Blueprint $table) {
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
        Schema::table('supplement_ingredients', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
