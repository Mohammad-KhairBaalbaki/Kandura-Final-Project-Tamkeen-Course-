<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session, default to 'en'
        $locale = Session::get('locale', config('app.locale', 'en'));

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}
