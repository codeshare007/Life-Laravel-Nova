<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSafetyInformationToOilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oils', function (Blueprint $table) {
            $table->unsignedInteger('safety_information_id')->nullable();
            $table->foreign('safety_information_id')->references('id')->on('safety_informations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oils', function (Blueprint $table) {
            $table->dropForeign(['safety_information_id']);
            $table->dropColumn('safety_information_id');
        });
    }
}
