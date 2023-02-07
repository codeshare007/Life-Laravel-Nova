<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetRecipeCategoryDefaultValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('type')->default(\App\Enums\CategoryType::Recipe)->change();
            $table->string('color', 6)->default('11111')->change();
            $table->string('image_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('type')->change();
            $table->string('color', 6)->change();
        });
    }
}
