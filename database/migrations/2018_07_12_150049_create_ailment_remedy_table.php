<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentRemedyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_remedy', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('remedy_id');
            $table->foreign('remedy_id')->references('id')->on('remedies')->onDelete('cascade');
            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');

            // Constraints
            $table->unique(['ailment_id', 'remedy_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_remedy');
    }
}
