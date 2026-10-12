<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->redirectUsersTo(fn () => auth()->user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard'));
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return back()->with('error', 'The total size of your upload is too large. Please try again with smaller files.');
        });
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            return back()->with('error', 'Too many attempts. Please wait a moment before trying again.');
        });
    })->create();
