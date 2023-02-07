<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameStreamIdToCommentIdOnCommentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_reports', function (Blueprint $table) {
            $table->renameColumn('stream_id', 'comment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_reports', function (Blueprint $table) {
            $table->renameColumn('comment_id', 'stream_id');
        });
    }
}
