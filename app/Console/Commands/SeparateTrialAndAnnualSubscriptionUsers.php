<?php

namespace App\Console\Commands;

use App\User;
use App\Enums\SubscriptionType;
use Illuminate\Console\Command;

class SeparateTrialAndAnnualSubscriptionUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:separate-trial-and-annual-subscription-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Separate the existing annual subscription users from the trial users';

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
        User::where('subscription_type', SubscriptionType::TheEssentialLifeMembership12Month)->each(function(User $user) {
            if ($user->subscription_expires_at < now()->addDays(8)) {
                $user->subscription_type = SubscriptionType::TheEssentialLifeMembershipTrial;
                $user->save();
            }
        });
    }
}
