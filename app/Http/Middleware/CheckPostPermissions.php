<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckPostPermissions
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed|Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return Inertia::location('/');
        }
        $postUserId = $request->route('post');
        if ($postUserId) {
            $loggedInUserId = Auth::id();

            if ($postUserId->user_id !== $loggedInUserId) {
                return Inertia::location('/');
            }
        }

        return $next($request);
    }
}
