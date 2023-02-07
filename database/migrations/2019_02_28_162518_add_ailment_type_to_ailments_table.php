<?php

use App\Enums\AilmentType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAilmentTypeToAilmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ailments', function (Blueprint $table) {
            $table->tinyInteger('ailment_type')->default(AilmentType::Ailment);
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
            $table->dropColumn('ailment_type');
        });
    }
}
