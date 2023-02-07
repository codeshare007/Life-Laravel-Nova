<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('supplements')) {
            Schema::create('supplements', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('name', 100);
                $table->text('image_url')->nullable();
                $table->string('color', 6)->nullable();
                $table->longText('fact');
                $table->longText('research')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->unsignedInteger('safety_information_id')->nullable();
                $table->unsignedInteger('sort_order')->default(0);

                $table->foreign('safety_information_id')->references('id')->on('safety_informations')->onDelete('cascade');
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
        Schema::dropIfExists('supplements');
    }
}
