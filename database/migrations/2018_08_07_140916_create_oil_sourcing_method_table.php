<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOilSourcingMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oil_sourcing_method', function (Blueprint $table) {
            // Foreign keys
            $table->unsignedInteger('oil_id');
            $table->foreign('oil_id')->references('id')->on('oils')->onDelete('cascade');
            $table->unsignedInteger('sourcing_method_id');
            $table->foreign('sourcing_method_id')->references('id')->on('sourcing_methods')->onDelete('cascade');

            // Constraints
            $table->unique(['oil_id', 'sourcing_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oil_sourcing_method');
    }
}
