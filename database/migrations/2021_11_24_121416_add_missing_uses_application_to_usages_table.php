<?php

use App\Usage;
use Illuminate\Database\Migrations\Migration;

class AddMissingUsesApplicationToUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Usage::whereNull('uses_application')->update(['uses_application' => Usage::defaultUsesApplication()]);
    }
}
