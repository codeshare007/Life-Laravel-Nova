<?php

use App\Enums\CommentReport\ActionTaken;
use App\Enums\CommentReport\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('reporter_id');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('commenter_id');
            $table->foreign('commenter_id')->references('id')->on('users')->onDelete('cascade');

            $table->longText('reason');
            $table->longText('comment');
            $table->string('stream_id');
            $table->tinyInteger('status')->default(Status::Open);
            $table->tinyInteger('action_taken')->default(ActionTaken::None);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_reports');
    }
}
