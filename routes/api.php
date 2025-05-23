<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CJController;
use App\Http\Controllers\SalesController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Add module routes
$modulePath = base_path('modules');
$modules = File::directories($modulePath);

foreach ($modules as $module) {
    $routeFile = $module . '/Routes/api.php';
    if (File::exists($routeFile)) {
        Route::middleware([])->group($routeFile);
    }
}

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store'])->middleware('admin');
    Route::put('users/me', [UserController::class, 'updateMyUser']);
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('admin');
    Route::post('users/hubspot-update', [UserController::class, 'updateByHubspot']);
    
    // Sales routes
    Route::apiResource('sales', SalesController::class);

    // CJ routes
    Route::get('cj/get-commissions', [CJController::class, 'getCommissionDetails'])
        ->where('publisher_id', '[0-9]+');
});

Route::get('/api-docs', function () {
    return collect(Route::getRoutes())
        ->map(function ($route) {
            $action = $route->action['controller'] ?? null;

            // Check if there is an associated controller
            $parameters = [];
            if ($action && Str::contains($action, '@')) {
                [$controller, $method] = explode('@', $action);
                $reflectionMethod = new ReflectionMethod($controller, $method);

                // Search if there is a Request as the first argument
                $parameters = collect($reflectionMethod->getParameters())
                    ->filter(fn($param) => $param->getClass() && $param->getClass()->isSubclassOf(\Illuminate\Foundation\Http\FormRequest::class))
                    ->flatMap(function ($param) {
                        $requestClass = $param->getClass()->name;
                        return (new $requestClass())->rules();
                    })
                    ->toArray();
            }

            return [
                'method' => implode(', ', $route->methods),
                'uri' => $route->uri,
                'name' => $route->getName(),
                'middleware' => $route->action['middleware'] ?? [],
                'parameters' => $parameters,
            ];
        });
});