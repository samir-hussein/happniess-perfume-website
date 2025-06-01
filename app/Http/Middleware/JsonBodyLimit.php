<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonBodyLimit
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$contentLength = $request->header('Content-Length');
		$contentType = $request->header('Content-Type');

		if ($contentType === 'application/json' && $contentLength > 512) {
			return response()->json(['message' => 'JSON too large'], 413);
		}

		return $next($request);
	}
}
