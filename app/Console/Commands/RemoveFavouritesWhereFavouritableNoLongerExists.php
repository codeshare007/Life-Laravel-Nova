<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Favourite;

class RemoveFavouritesWhereFavouritableNoLongerExists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:remove-favourites-where-favouritable-no-longer-exists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the favourites where the favouritable item no longer exists.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $toDelete = [];

        Favourite::all()->each(function(Favourite $favourite) use (&$toDelete) {

            if($favourite->favouriteable === null) {
                $this->info("Can delete favourite ($favourite->id) for user $favourite->user_id. Favouriteable type: $favourite->favouriteable_type Favouriteable ID: $favourite->favouriteable_id");

                $toDelete[] = $favourite->id;
            }

        });

        $toDeleteCount = count($toDelete);

        if ($toDeleteCount > 0) {
            if ($this->confirm("Do you want to delete the $toDeleteCount favourites?")) {
                Favourite::destroy($toDelete);
                $this->info("Deleted $toDeleteCount favourites.");
            } else {
                $this->info("Cancelled.");
            }
        } else {
            $this->info("There are no favourites where the favouriteable doesn't exist.");
        }

    }
}
