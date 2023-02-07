<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplementAilmentUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('supplement_ailment_usage')) {
            Schema::create('supplement_ailment_usage', function (Blueprint $table) {
                $table->increments('supplement_ailment_id');

                $table->unsignedInteger('supplement_id');
                $table->foreign('supplement_id')->references('id')->on('supplements')->onDelete('cascade');
                $table->unsignedInteger('ailment_id');
                $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');
                $table->integer('sort_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplement_ailment_usage');
    }
}
