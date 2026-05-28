<?php

use App\Console\Commands\DbBackup;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\RoutesAsPermissions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checkUserAuth' => \App\Http\Middleware\CheckUserAuth::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withCommands([
        RoutesAsPermissions::class,
        DbBackup::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
