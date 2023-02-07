<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserLanguage;
use App\Services\LanguageDatabaseService;

class UserLanguageMiddleware
{
    protected $languageDatabaseService;

    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        $this->languageDatabaseService = $languageDatabaseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment('testing')) {
            return $next($request);
        }

        $lang = $request->route()->parameter('lang');

        if (! UserLanguage::hasValue($lang)) {
            abort(404, 'Language does not exist.');
            $lang = UserLanguage::English;
        }

        $this->languageDatabaseService->setLanguage(UserLanguage::getInstance($lang));

        return $next($request);
    }
}
