<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentSymptomGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_symptom_group', function (Blueprint $table) {
            // Foreign keys
            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');
            $table->unsignedInteger('symptom_group_id');
            $table->foreign('symptom_group_id')->references('id')->on('symptom_groups')->onDelete('cascade');

            // Constraints
            $table->unique(['ailment_id', 'symptom_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_symptom_group');
    }
}
