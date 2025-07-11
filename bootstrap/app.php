<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AssignGuestChatId;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->alias([
			'local' => \App\Http\Middleware\SetLocale::class,
		]);

		$middleware->append(AssignGuestChatId::class);

		$middleware->redirectGuestsTo(function () {
			return route('login', ['locale' => app()->getLocale()]);
		});

		$middleware->redirectUsersTo(fn() => route('home', ['locale' => app()->getLocale()]));
	})
	->withExceptions(function (Exceptions $exceptions) {
		//
	})->create();
