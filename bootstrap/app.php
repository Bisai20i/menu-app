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
        $middleware->alias([
            'superadmin' => SuperAdminUserMiddleware::class
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
        
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                // Check if this is a file upload with validation error
                $errors = $e->validator->errors()->toArray();
                foreach ($errors as $field => $messages) {
                    if (str_contains($field, 'image') || str_contains($field, 'file')) {
                        // Already formatted with friendly messages from controller
                        break;
                    }
                }
            }
            return back()->withErrors($e->errors())->withInput();
        });
    })->create();
