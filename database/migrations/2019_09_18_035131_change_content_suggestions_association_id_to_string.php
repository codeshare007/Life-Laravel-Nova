<?php

use App\ContentSuggestion;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeContentSuggestionsAssociationIdToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $contentToUUID = ContentSuggestion::all()->filter(function ($content) {
            return $content->association_id !== 0 && $content->association_type;
        })->map(function ($content) {
            $association = (new $content->association_type);
            $model = $association->find($content->association_id);
            if (! $model) {
               return;
            }
            $uuid = $model->uuid;
            return [
                'id' => $content->id,
                'uuid' => $uuid
            ];
        })->filter();

        Schema::table('content_suggestions', function (Blueprint $table) {
            $table->string('association_id')->default(0)->change();
        });

        $contentToUUID->each(function ($content) {
            \DB::table('content_suggestions')
                ->where('id', $content['id'])
                ->update(['association_id' => $content['uuid']]);
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
            $table->unsignedInteger('association_id')->default(0)->change();
        });
    }
}
