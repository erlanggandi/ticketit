<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareSiteSettingMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            View::share('siteSetting', SiteSetting::currentOrCreate());
        } catch (\Exception) {
            // Table may not exist yet (e.g. during installation or tests without migrations)
        }

        return $next($request);
    }
}
