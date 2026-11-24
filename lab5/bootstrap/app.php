<?php

use App\Exceptions\CategoryNotEmpty;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Для /api/* помилки повертаються виключно у форматі JSON
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Обробка бізнес-винятку: видалення категорії, що містить товари -> 409 Conflict
        $exceptions->render(function (CategoryNotEmpty $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        });

        // 404 у форматі JSON для API
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $prev = $e->getPrevious();
                $message = $prev instanceof ModelNotFoundException
                    ? 'Запис не знайдено.'
                    : 'Маршрут не знайдено.';

                return response()->json(['message' => $message], 404);
            }
        });
    })->create();
