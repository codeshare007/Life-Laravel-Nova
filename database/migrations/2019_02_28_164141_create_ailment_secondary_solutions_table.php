<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentSecondarySolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_secondary_solutions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('sort_order')->default(0);

            // Foreign keys
            $table->string('solutionable_type', 100)->nullable();
            $table->unsignedInteger('solutionable_id')->nullable();

            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_secondary_solutions');
    }
}
