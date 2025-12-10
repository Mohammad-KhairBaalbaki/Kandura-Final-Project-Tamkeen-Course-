<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocaleMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle NotFoundHttpException
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // For API routes - return JSON
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                    'data' => null,
                ], 404);
            }

            // For web routes - return custom view
            return response()->view('errors.404', [
                'message' => 'Page not found',
            ], 404);
        });

    })->create();
