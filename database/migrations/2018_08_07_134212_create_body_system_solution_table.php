<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodySystemSolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_system_solution', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('body_system_id');
            $table->foreign('body_system_id')->references('id')->on('body_systems')->onDelete('cascade');
            $table->unsignedInteger('solution_id');
            $table->foreign('solution_id')->references('id')->on('solutions')->onDelete('cascade');

            // Constraints
            $table->unique(['body_system_id', 'solution_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_system_solution');
    }
}
