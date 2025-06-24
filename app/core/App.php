<?php

class App
{
    private string $controller = 'Home';
    private string $method = 'index';

    private function getQueryString(): array|string
    {
        $url = $_SERVER['REQUEST_URI'];
        if ($url !== '/') {
            return explode('/', trim($url, '/'));
        }

        return '/';
    }

    public function loadController(): void
    {
        $url = $this->getQueryString();
        $fileName = "../app/controllers/" . ucfirst($url[0]) . ".php";
        if (file_exists($fileName)) {
            require $fileName;
            $this->controller = ucfirst($url[0]);
        } else {
            require "../app/controllers/_404.php";
            $this->controller = "_404";
        }

        $controller = new $this->controller;
        call_user_func_array([$controller, $this->method], []);

    }
}
