<?php

use App\Constant;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApiMiddleware;
use App\Http\Middleware\IsEmailVerify;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            ApiMiddleware::class,
        ]);
        
        $middleware->alias([
            'admin'         => AdminMiddleware::class,
            'isEmailVerify' => IsEmailVerify::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (BadRequestHttpException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage() ?? Constant::ERROR_MESSAGE_BAD_REQUEST
            ], status: 400);
        });
        $exceptions->render(function (UnauthorizedHttpException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage() ?? Constant::ERROR_MESSAGE_BAD_REQUEST
            ], status: 401);
        });
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => Constant::ERROR_MESSAGE_UNAUTHORIZED
            ], status: 401);
        });

        $exceptions->render(function (AccessDeniedHttpException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => Constant::ERROR_MESSAGE_UNAUTHORIZED
            ], status: 403);
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => Constant::ERROR_MESSAGE_NOT_FOUND
            ], status: 404);
        });
    })->create();
