<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonToUsersGeneratedContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_generated_content', function (Blueprint $table) {
            $table->unsignedInteger('reason_id')->nullable();
            $table->longText('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_generated_content', function (Blueprint $table) {
            $table->dropColumn('reason_id');
            $table->dropColumn('reason');
        });
    }
}
