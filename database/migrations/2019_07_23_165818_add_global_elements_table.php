<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlobalElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('global_elements')) {
            Schema::create('global_elements', function (Blueprint $table) {
                $table->uuid('id');
                $table->timestamps();

                // Foreign keys
                $table->string('element_type', 100);
                $table->unsignedInteger('element_id');

                // Indexes
                $table->index(['element_id', 'element_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('global_elements');
    }
}
