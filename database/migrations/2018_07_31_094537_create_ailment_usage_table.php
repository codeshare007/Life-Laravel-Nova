<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAilmentUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ailment_usage', function (Blueprint $table) {
            
            // Foreign keys
            $table->unsignedInteger('ailment_id');
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');
            $table->unsignedInteger('usage_id');
            $table->foreign('usage_id')->references('id')->on('usages')->onDelete('cascade');

            // Constraints
            $table->unique(['ailment_id', 'usage_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ailment_usage');
    }
}
