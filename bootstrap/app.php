<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e) {
            if (env('APP_DEBUG')) {
                return null;
            }

            $json   = JsonResponse::build($e);
            $data   = json_decode($json, true);
            $status = $data['status'] ?? 500;

            return response($json, $status)
                ->header('Content-Type', 'application/json');
        });
    })->create();
