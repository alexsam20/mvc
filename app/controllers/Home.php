<?php
namespace Controller;

use Models\User;

defined('ROOTPATH') OR exit('Access Denied!');
class Home
{
    use Controller;

    public function index()
    {
        $user = new User();
        print_pre($user);

        $this->view('home');
    }
}
