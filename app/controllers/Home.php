<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $d = '')
    {
        $user = new User();
        $result = $user->findAll();

        print_pre($result);
        $this->view('home');
    }
}
