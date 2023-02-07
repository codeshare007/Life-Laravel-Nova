<?php

use App\Card;
use App\Enums\Region;
use Illuminate\Database\Migrations\Migration;

class AddAllRegionsToExistingCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Card::query()->update(['regions' => json_encode(Region::getValues())]);
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
