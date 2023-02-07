<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectionReasonDescriptionToUsersGeneratedContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_generated_content', function (Blueprint $table) {
            $table->longText('rejection_reason_description')->nullable();
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
            $table->dropColumn('rejection_reason_description');
        });
    }
}
