<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortOrderToOilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('oils', 'sort_order')) {
            Schema::table('oils', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oils', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
