<?php
// ==============================================
// File: backend/core/Router.php
// ==============================================

// ==============================================
// Core Router class (English)
// Handles routing requests to controllers and methods
// ==============================================

// ==============================================
// کلاس مسیریاب اصلی (فارسی)
// مدیریت مسیرها و ارسال درخواست‌ها به کنترلرها و متدها
// ==============================================

class Router {
    private $routes = [];

    // EN: Add a route
    // FA: اضافه کردن مسیر
    public function add($method, $uri, $callback) {
        $this->routes[] = ['method' => strtoupper($method), 'uri' => $uri, 'callback' => $callback];
    }

    // EN: Dispatch request to proper route
    // FA: هدایت درخواست به مسیر مناسب
    public function dispatch($requestUri, $requestMethod) {
        foreach($this->routes as $route) {
            if($route['method'] === strtoupper($requestMethod) && $route['uri'] === $requestUri){
                if(is_callable($route['callback'])){
                    call_user_func($route['callback']);
                    return;
                }
            }
        }
        // EN: No route matched
        // FA: هیچ مسیری پیدا نشد
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Route not found']);
    }
}