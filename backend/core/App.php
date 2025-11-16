<?php
// ==============================================
// File: backend/core/App.php
// ==============================================

// ==============================================
// Core App class (English)
// Handles basic app bootstrapping and routing
// ==============================================

// ==============================================
// کلاس اصلی برنامه (فارسی)
// مدیریت بوت‌استرپ اولیه و مسیر یابی ساده
// ==============================================

class App {
    protected $controller = 'index';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // EN: Set controller if exists
        // FA: تنظیم کنترلر در صورتی که وجود داشته باشد
        if(file_exists('../controllers/' . ucfirst($url[0]) . 'Controller.php')){
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // EN: Set method if exists
        // FA: تنظیم متد در صورتی که وجود داشته باشد
        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // EN: Set parameters
        // FA: تنظیم پارامترها
        $this->params = $url ? array_values($url) : [];

        // EN: Call the controller method with params
        // FA: فراخوانی متد کنترلر با پارامترها
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // EN: Parse URL into array
    // FA: تبدیل URL به آرایه
    private function parseUrl(){
        if(isset($_GET['url'])){
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [$this->controller];
    }
}