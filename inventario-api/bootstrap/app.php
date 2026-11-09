<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //$middleware->redirectGuestsTo(fn (Request $request) => $request->is('api/*') ? null : route('login'));
        // me di cuenta algo tarde que la evaluacion no pedia sanctum, asi que solo quite el middleware y las rutas
        // no pasa nada si el paquete sigue instalado... asi que asi lo dejo, la costumbre, del otro parcial si habia sanctum
        // sin embargo, la evaluacion explicitamente menciona aceptar peticiones API desde el origen de vue... eso por defecto esta
        // pero... implicitamente la evaluacion me pide CORS? lo pondre por si acaso, comentado
        // aqui meti mi reflexion pero esta en config/cors.php, se usa php artisan config:publish cors para que aparezca en laravel 13
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
