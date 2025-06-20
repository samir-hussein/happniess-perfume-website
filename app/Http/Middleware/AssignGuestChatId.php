<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AssignGuestChatId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasCookie('chat_id')) {
            $chatId = (string) Str::uuid();

            // Temporarily add it to the request so other middlewares/controllers can use it
            $request->cookies->set('chat_id', $chatId);

            // Attach the cookie to the response
            return $next($request)->cookie('chat_id', $chatId, 60 * 24 * 365);
        }

        return $next($request);
    }
}
