<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToUserGeneratedContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_generated_content', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
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
            $table->dropColumn('uuid');
        });
    }
}
