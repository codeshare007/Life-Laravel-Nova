<?php

use App\Element;
use App\AilmentSolution;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToAilmentSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ailment_solutions', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable('false');
        });

        AilmentSolution::all()->each(function($model) {
            if ($model->uuid === null) {
                $uuid = Str::uuid();
    
                Element::create([
                    'id' => $uuid,
                    'element_type' => get_class($model),
                    'element_id' => $model->id,
                ]);
    
                AilmentSolution::withoutEvents(function () use ($model, $uuid) {
                    $model->uuid = $uuid;
                    $model->save();
                });
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
        Schema::table('ailment_solutions', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
