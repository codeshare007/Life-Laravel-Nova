<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmotionsIndexToOilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oils', function (Blueprint $table) {
            $table->text('emotion_from')->nullable();
            $table->text('emotion_to')->nullable();
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
            $table->dropColumn('emotion_from');
            $table->dropColumn('emotion_to');
        });
    }
}
