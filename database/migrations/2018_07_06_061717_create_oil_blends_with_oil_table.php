<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOilBlendsWithOilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oil_blends_with_oil', function (Blueprint $table) {

            // Foreign keys
            $table->unsignedInteger('oil_id');
            $table->foreign('oil_id')->references('id')->on('oils')->onDelete('cascade');
            $table->unsignedInteger('blends_with_oil_id');
            $table->foreign('blends_with_oil_id')->references('id')->on('oils')->onDelete('cascade');

            // Constraints
            $table->unique(['oil_id', 'blends_with_oil_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oil_blends_with_oil');
    }
}
