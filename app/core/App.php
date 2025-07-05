<?php
defined('ROOTPATH') OR exit('Access Denied!');
class App
{
    private string $controller = 'Home';
    private string $method = 'index';

    private function getQueryString(): array|string
    {
        $url = $_SERVER['REQUEST_URI'] ?? 'home';
        if ($_SERVER['REQUEST_URI'] === '/') {
            $url = 'home';
        }
        return explode('/', trim($url, '/'));
    }

    public function loadController(): void
    {
        $url = $this->getQueryString();
        /** Select Controller **/
        $fileName = "../app/controllers/" . ucfirst($url[0]) . ".php";
        if (file_exists($fileName)) {
            require $fileName;
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        } else {
            require "../app/controllers/_404.php";
            $this->controller = "_404";
        }
        $controller = new ('\Controller\\' . $this->controller);

        /** Select Method **/
        if (!empty($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        call_user_func_array([$controller, $this->method], $url);

    }
}
