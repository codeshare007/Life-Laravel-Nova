<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfExtractConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_extract_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pdf_id')->unsigned();
            $table->integer('page')->unsigned();
            $table->text('body_system')->nullable();
            $table->integer('solutions_nb')->unsigned();
            $table->text('name')->nullable();
            $table->longText('content')->nullable();
            $table->longText('solutions')->nullable();
            $table->longText('linked_body_system')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_extract_conditions');
    }
}
