<?php

class Logout
{
    use Controller;

    public function index()
    {
//        $data['user'] = empty($_SESSION['user']) ? 'User' : $_SESSION['user']->email;
        if (!empty($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        redirect('home');
    }
}