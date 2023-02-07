<?php

use App\Enums\UserGeneratedContentStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersGeneratedContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_generated_content', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->text('name');
            $table->text('type');

            $table->json('content')->nullable();
            $table->integer('status')->default(UserGeneratedContentStatus::InReview);
            $table->tinyInteger('is_public')->default(0);

            // Foreign keys
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_generated_content');
    }
}
