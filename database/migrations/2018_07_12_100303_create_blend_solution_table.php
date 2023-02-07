<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlendSolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blend_solution', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('blend_id');
            $table->foreign('blend_id')->references('id')->on('blends')->onDelete('cascade');
            $table->unsignedInteger('solution_id');
            $table->foreign('solution_id')->references('id')->on('solutions')->onDelete('cascade');

            // Constraints
            $table->unique(['blend_id', 'solution_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blend_solution');
    }
}
