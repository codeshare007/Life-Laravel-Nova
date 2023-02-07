<?php

namespace App\Console\Commands;

use App\User;
use App\Enums\SubscriptionType;
use Illuminate\Console\Command;

class BulkApplyBypass extends Command
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:bulk-apply-bypass';

    protected $emails = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk applies the bypass to users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->emails as $email) {
            $user = User::inAnyDbFindBy('email', $email);
            if ($user) {
                /** @var User $user */
                $user->enableBypass();

                $this->info("Applied bypass for $email");
            } else {
                $this->error("Could not find account for for $email");
            }
        }
    }
}
