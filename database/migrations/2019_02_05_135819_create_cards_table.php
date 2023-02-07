<?php

use App\Enums\CardTextStyle;
use App\Enums\CardVerticalAlignment;
use App\Enums\CardHorizontalAlignment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('is_active')->default(false);
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->text('url')->nullable();
            $table->text('button_text')->nullable();
            $table->text('image_url')->nullable();
            $table->text('header_image_url')->nullable();
            $table->string('background_color', 6)->nullable();
            $table->unsignedInteger('text_style')->default(CardTextStyle::Dark);
            $table->unsignedInteger('content_vertical_alignment')->default(CardVerticalAlignment::Top);
            $table->unsignedInteger('content_horizontal_alignment')->default(CardHorizontalAlignment::Left);
            $table->unsignedInteger('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
