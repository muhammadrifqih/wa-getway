<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            // For development MVP, let's just make the first user admin if they are ID 1
            if (auth()->check() && auth()->id() === 1) {
                $user = auth()->user();
                $user->is_admin = true;
                $user->save();
            } else {
                abort(403, 'Unauthorized. Admins only.');
            }
        }
        
        return $next($request);
    }
}
