<?php
defined('ROOTPATH') OR exit('Access Denied!');

spl_autoload_register(function ($className) {
    $className = explode('\\', $className);
    $className = end($className);
    require $filename = "../app/models/" . ucfirst($className) . ".php";
});

require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
