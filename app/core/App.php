<?php

class App
{
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
        } else {
            require "../app/controllers/_404.php";
        }
    }
}
