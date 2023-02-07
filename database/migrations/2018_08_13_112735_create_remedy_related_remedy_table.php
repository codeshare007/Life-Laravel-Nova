<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemedyRelatedRemedyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remedy_related_remedy', function (Blueprint $table) {
            // Foreign keys
            $table->unsignedInteger('remedy_id');
            $table->foreign('remedy_id')->references('id')->on('remedies')->onDelete('cascade');
            $table->unsignedInteger('related_remedy_id');
            $table->foreign('related_remedy_id')->references('id')->on('remedies')->onDelete('cascade');

            // Constraints
            $table->unique(['remedy_id', 'related_remedy_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remedy_related_remedy');
    }
}
