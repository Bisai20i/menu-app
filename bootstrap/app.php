<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SuperAdminUserMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/master/login');
        $middleware->alise([
            'superadmin' => SuperAdminUserMiddleware
        ]);

    })
    ->withBroadcasting(
        '/../routes/channels.php',
        [
            'middleware' => ['web', 'auth:admin'], 
        ],
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            return back()->with('error', 'The uploaded file is too large. Please upload images with size less than 5 MB.');
        });
    })->create();
