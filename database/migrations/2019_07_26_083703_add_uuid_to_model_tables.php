<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToModelTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ailments', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('oils', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('blends', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('body_systems', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('supplements', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('sourcing_methods', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('usages', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('remedies', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        Schema::table('recipes', function (Blueprint $table) {
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
        Schema::table('ailments', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('oils', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('blends', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('supplements', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('sourcing_methods', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('usages', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('body_systems', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('remedies', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
