<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentRelatedAilmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_related_ailment', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');
            $table->unsignedInteger('related_ailment_id');
            $table->foreign('related_ailment_id')->references('id')->on('ailments')->onDelete('cascade');

            // Constraints
            $table->unique(['ailment_id', 'related_ailment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_related_ailment');
    }
}
