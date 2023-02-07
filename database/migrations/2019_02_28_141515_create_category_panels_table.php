<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryPanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_panels', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->text('background_image_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_panels');
    }
}
