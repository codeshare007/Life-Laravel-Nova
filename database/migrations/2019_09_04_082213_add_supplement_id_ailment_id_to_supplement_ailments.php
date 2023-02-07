<?php

use App\Supplement;
use App\SupplementAilment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplementIdAilmentIdToSupplementAilments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplement_ailments', function (Blueprint $table) {
            $table->unsignedInteger('supplement_id')->nullable();
            $table->foreign('supplement_id')->references('id')->on('supplements')->onDelete('cascade');
            $table->unsignedInteger('ailment_id')->nullable();
            $table->foreign('ailment_id')->references('id')->on('ailments')->onDelete('cascade');
            $table->string('name')->nullable()->change();
        });

        \DB::table('supplement_ailment_usage')->get()->each(function ($usage) {
            $supplementAilment = SupplementAilment::find($usage->supplement_ailment_id);
            if ($usage->supplement_id && $usage->ailment_id) {
                $supplementAilment->supplement_id = $usage->supplement_id;
                $supplementAilment->ailment_id = $usage->ailment_id;
                $supplementAilment->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplement_ailments', function (Blueprint $table) {
            $table->dropForeign('supplement_ailments_supplement_id_foreign');
            $table->dropColumn('supplement_id');
            $table->dropForeign('supplement_ailments_ailment_id_foreign');
            $table->dropColumn('ailment_id');
            $table->string('name')->change();
        });
    }
}
