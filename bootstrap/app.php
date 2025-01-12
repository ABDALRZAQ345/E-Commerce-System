<?php

use App\Http\Middleware\CanCreateStore;
use App\Http\Middleware\CanEditStoreData;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\SameUser;
use App\Http\Middleware\XssProtection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'locale' => LocaleMiddleware::class,
            'xss' => XssProtection::class,
            'store_owner' => CanEditStoreData::class,
            'can_create_store' => CanCreateStore::class,
            'same_user' => SameUser::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Object not found.',
                ], 404);
            }

        });

    })
    ->create();
