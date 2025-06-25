<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $d = '')
    {
        echo __METHOD__;
        $db = new Database();
        $this->view('home');
    }
}
