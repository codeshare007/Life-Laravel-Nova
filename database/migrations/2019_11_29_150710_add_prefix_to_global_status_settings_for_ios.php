<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrefixToGlobalStatusSettingsForIos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Replace any key which starts with `status_` with `ios_status_`
        if (Schema::hasTable('settings') && ! app()->environment('testing')) {
            DB::table('settings')
                ->where('key', 'like', 'status_%')
                ->update([
                    'key' => DB::raw("CONCAT('ios_', `key`)")
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
