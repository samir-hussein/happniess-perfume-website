<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$locale = $request->segment(1);


		if (in_array($locale, array_keys(Config::get('app.locales')))) {
			App::setLocale($locale);
			$request->session()->put('locale', $locale);
		} else {
			App::setLocale(Config::get('app.locale'));
			$request->session()->put('locale', Config::get('app.locale'));
			if ($locale) {
				return redirect()->route('home', ['locale' => Config::get('app.locale')]);
			}
		}

		return $next($request);
	}
}
