<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodySystemRemedyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_system_remedy', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('body_system_id');
            $table->foreign('body_system_id')->references('id')->on('body_systems')->onDelete('cascade');
            $table->unsignedInteger('remedy_id');
            $table->foreign('remedy_id')->references('id')->on('remedies')->onDelete('cascade');

            // Constraints
            $table->unique(['body_system_id', 'remedy_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_system_remedy');
    }
}
