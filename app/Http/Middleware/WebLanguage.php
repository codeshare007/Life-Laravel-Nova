<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\LanguageDatabaseService;

class WebLanguage
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
        if (session()->has('selected_language')) {
            $this->languageDatabaseService->setLanguage(session()->get('selected_language'));
        }

        return $next($request);
    }
}
