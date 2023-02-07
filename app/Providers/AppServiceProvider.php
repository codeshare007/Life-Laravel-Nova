<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use App\Services\LanguageDatabaseService;
use Illuminate\Support\Facades\Validator;
use App\Services\CommentService\CommentService;
use App\Services\QuestionService\QuestionService;
use App\Services\CommentService\FirebaseCommentService;
use App\Services\QuestionService\FirebaseQuestionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('effectiveSolutionsLimit', function($attribute, $value, $parameters) {
            return DB::table('ailment_solution')->where('ailment_id', Request::segment(3))->count() < 5;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LanguageDatabaseService::class, function ($app) {
            return new LanguageDatabaseService;
        });

        $this->app->singleton(QuestionService::class, FirebaseQuestionService::class);
        $this->app->singleton(CommentService::class, FirebaseCommentService::class);
    }
}
