<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagables', function (Blueprint $table) {
            // Foreign keys
            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unsignedInteger('tagable_id');
            $table->string('tagable_type', 100);

            // Constraints
            $table->unique(['tag_id', 'tagable_id', 'tagable_type'], 'unique_tagable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagables');
    }
}
