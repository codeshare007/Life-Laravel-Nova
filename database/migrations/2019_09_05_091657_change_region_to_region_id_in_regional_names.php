<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRegionToRegionIdInRegionalNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regional_names', function (Blueprint $table) {
            $table->dropColumn('region');
        });

        Schema::table('regional_names', function (Blueprint $table) {
            $table->unsignedInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regional_names', function (Blueprint $table) {
            $table->dropForeign('regional_names_region_id_foreign');
            $table->dropColumn('region_id');
            $table->integer('region');
        });
    }
}
