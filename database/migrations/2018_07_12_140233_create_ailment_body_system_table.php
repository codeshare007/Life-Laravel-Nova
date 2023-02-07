<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentBodySystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_body_system', function (Blueprint $table) {

            // Foreign keys
            $table->unsignedInteger('body_system_id');
            $table->foreign('body_system_id')->references('id')->on('body_systems')->onDelete('cascade');
            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');

            // Constraints
            $table->unique(['body_system_id', 'ailment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_body_system');
    }
}
