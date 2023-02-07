<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssociationTypeAndAssociationIdToBeNullableOnContentSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_suggestions', function (Blueprint $table) {
            $table->longText('association_type')->nullable()->change();
            $table->unsignedInteger('association_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_suggestions', function (Blueprint $table) {
            $table->longText('association_type')->nullable(false)->change();
            $table->unsignedInteger('association_id')->nullable(false)->change();
        });
    }
}
