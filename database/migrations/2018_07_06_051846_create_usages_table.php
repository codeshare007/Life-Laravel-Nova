<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->longText('description');

            // Foreign keys
            $table->unsignedInteger('useable_id');
            $table->string('useable_type', 100);

            // Constraints
            // $table->unique(['useable_id', 'useable_type'], 'unique_useable');

            // Indexes
            $table->index(['useable_id', 'useable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usages');
    }
}
