<?php
namespace App\Http\Middleware;

use App\Models\Responses\NSHResponse;
use Closure;

class ApiKeyCheck
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment('production', 'staging') && $request->getMethod() != 'OPTIONS') {
            // Require API Key when environment is either production OR staging...
            if (empty($request->header('NSH-API-KEY'))) {
                $nsh_response = new NSHResponse(403, 177);
                return $nsh_response->render();
            }

            if ($request->header('NSH-API-KEY') != env('API_KEY')) {
                $nsh_response = new NSHResponse(403, 179);
                return $nsh_response->render();
            }
        }
        return $next($request);
    }
}
