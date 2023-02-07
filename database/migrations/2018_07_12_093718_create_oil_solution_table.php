<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOilSolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oil_solution', function (Blueprint $table) {

            // Foreign keys
            $table->unsignedInteger('oil_id');
            $table->foreign('oil_id')->references('id')->on('oils')->onDelete('cascade');
            $table->unsignedInteger('solution_id');
            $table->foreign('solution_id')->references('id')->on('solutions')->onDelete('cascade');

            // Constraints
            $table->unique(['oil_id', 'solution_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oil_solution');
    }
}
