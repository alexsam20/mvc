<?php

trait Controller
{
    public function view($name, $data = []): void
    {
        if (!empty($data)) {
            extract($data);
        }
        $fileName = "../app/views/" . $name . ".view.php";
        if (file_exists($fileName)) {
            require $fileName;
        } else {
            require "../app/views/404.view.php";
        }
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}