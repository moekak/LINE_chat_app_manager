<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Sentry\Laravel\Integration;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php' // APIルートを保存
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo("/login");
        $middleware->redirectUsersTo("/");

    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();
