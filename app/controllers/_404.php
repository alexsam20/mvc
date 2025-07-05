<?php
namespace Controllers;

use Controller\Controller;

defined('ROOTPATH') OR exit('Access Denied!');
class _404
{
    use Controller;

    public function index()
    {
        echo "404 Page Not Found Controller :(";
    }
}
