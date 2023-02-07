<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfExtractDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_extract_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pdf_id')->unsigned();
            $table->integer('area_id')->unsigned();
            $table->integer('page')->unsigned();
            $table->text('column')->nullable();
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('pdf_extract_data');
    }
}
