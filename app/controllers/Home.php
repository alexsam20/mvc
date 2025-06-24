<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $d = '')
    {
        echo __METHOD__;
        $this->view('home');
    }
}
