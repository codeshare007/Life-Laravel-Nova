<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            // Foreign keys
            $table->unsignedInteger('collection_id');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->unsignedInteger('collectable_id');
            $table->string('collectable_type', 100);

            // Constraints
            $table->unique(['collection_id', 'collectable_id', 'collectable_type'], 'unique_collectable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectables');
    }
}
