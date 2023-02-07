<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentBlendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_blend', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');
            $table->unsignedInteger('blend_id');
            $table->foreign('blend_id')->references('id')->on('blends')->onDelete('cascade');

            // Constraints
            $table->unique(['ailment_id', 'blend_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_blend');
    }
}
