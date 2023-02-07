<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalElementsDeletedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_elements_deleted', function (Blueprint $table) {
            $table->uuid('id');
            $table->timestamps();

            // Foreign keys
            $table->string('element_type', 100);
            $table->unsignedInteger('element_id');
            $table->json('element_attributes')->nullable();

            // Indexes
            $table->index(['element_id', 'element_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_elements_deleted');
    }
}
