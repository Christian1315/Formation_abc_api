<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return IsUserAnAdmin();
        if (!IsUserAnAdmin()) {
            return response()->json([
                "status" => false,
                "message" => "Seuls les admins sont autorisés à éffectruer cette opération",
            ]);
        }
        return $next($request);
    }
}
