<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOilFoundInSolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oil_found_in_solution', function (Blueprint $table) {

            // Foreign keys
            $table->unsignedInteger('oil_id');
            $table->foreign('oil_id')->references('id')->on('oils')->onDelete('cascade');
            $table->unsignedInteger('found_in_solution_id');
            $table->foreign('found_in_solution_id')->references('id')->on('solution_groups')->onDelete('cascade');

            // Constraints
            $table->unique(['oil_id', 'found_in_solution_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oil_found_in_solution');
    }
}
