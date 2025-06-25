<?php
$protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
$protocol = strtolower($protocol[0]);
$host = $protocol . "://" . $_SERVER['HTTP_HOST'];
define("ROOT", dirname(__DIR__, 2));
define("DOCUMENT_ROOT", $host);

// DB Params
const DB_HOST = 'localhost';
const DB_USER = 'alex';
const DB_PASS = 'alex1970MD3214';
const DB_NAME = 'mvc';