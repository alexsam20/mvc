<?php

trait Controller
{
    public function view($name): void
    {
        $fileName = "../app/views/" . $name . ".view.php";
        if (file_exists($fileName)) {
            require $fileName;
        } else {
            require "../app/views/404.view.php";
        }
    }
}