<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserIdToBeNullableOnRemediesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remedies', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->unsignedInteger('user_id')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remedies', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->unsignedInteger('user_id')->default(0)->change();
        });
    }
}
