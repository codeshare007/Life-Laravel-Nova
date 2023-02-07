<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSolutionableToSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->unsignedInteger('solutionable_id')->nullable();
            $table->string('solutionable_type', 100)->nullable();
        });

        Schema::table('solutions', function (Blueprint $table){
            $table->unsignedInteger('solutionable_id')->nullable(false)->change();
            $table->string('solutionable_type', 100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn('solutionable_id');
            $table->dropColumn('solutionable_type');
        });
    }
}
