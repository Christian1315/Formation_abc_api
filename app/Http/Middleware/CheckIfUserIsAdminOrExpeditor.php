<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserIsAdminOrExpeditor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!IsUserAnAdminOrExpeditor(request()->user()->id)) {
            return response()->json([
                "status" => false,
                "message" => "Seuls les admins et les Expéditeurs sont autorisés à éffectuer cette opération",
            ]);
        }
        return $next($request);
    }
}
