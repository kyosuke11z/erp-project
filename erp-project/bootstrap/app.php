<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // คืนค่า Middleware Alias ของ Spatie ที่จำเป็นกลับมา
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // เพิ่มโค้ดสำหรับจัดการ Unauthenticated API requests
        $middleware->redirectGuestsTo(function (Request $request) {
            // ถ้า request เป็น API (ขึ้นต้นด้วย api/)
            if ($request->is('api/*')) {
                // ไม่ต้อง redirect แต่ให้ Laravel จัดการเป็น JSON error 401
                return null;
            }
 
            // สำหรับ request ปกติ (web) ให้ redirect ไปหน้า login
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ดักจับ AuthenticationException โดยเฉพาะ
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            // ถ้า request เป็น API request ให้ส่ง JSON response กลับไปเสมอ
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
            });

        // ดักจับ ValidationException เพื่อให้ API ส่ง JSON error เสมอ
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(), // ข้อความเช่น "The given data was invalid."
                    'errors' => $e->errors(),
                ], 422); // 422 Unprocessable Entity
            }
        });
    })->create();
