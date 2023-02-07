<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlendOilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blend_oil', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('blend_id');
            $table->foreign('blend_id')->references('id')->on('blends')->onDelete('cascade');
            $table->unsignedInteger('oil_id');
            $table->foreign('oil_id')->references('id')->on('oils')->onDelete('cascade');

            // Constraints
            $table->unique(['oil_id', 'blend_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blend_oil');
    }
}
