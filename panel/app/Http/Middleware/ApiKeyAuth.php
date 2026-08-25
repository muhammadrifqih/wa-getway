<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiKey;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized. API Key is missing.'], 401);
        }

        // Validate token format wa_live_xxxx
        if (!str_starts_with($token, 'wa_live_')) {
            return response()->json(['error' => 'Invalid API Key format.'], 401);
        }

        $hash = hash('sha256', $token);
        
        $apiKey = ApiKey::where('key_hash', $hash)
            ->where('is_active', true)
            ->first();

        if (!$apiKey) {
            return response()->json(['error' => 'Unauthorized. Invalid or inactive API Key.'], 401);
        }

        // Check expiration
        if ($apiKey->expires_at && $apiKey->expires_at->isPast()) {
            return response()->json(['error' => 'Unauthorized. API Key has expired.'], 401);
        }

        // Update last used
        $apiKey->update(['last_used_at' => now()]);

        // Attach user to request
        $request->setUserResolver(function () use ($apiKey) {
            return $apiKey->user;
        });
        \Illuminate\Support\Facades\Auth::setUser($apiKey->user);

        // Add API Key object to request for reference
        $request->attributes->set('api_key_id', $apiKey->id);

        return $next($request);
    }
}
