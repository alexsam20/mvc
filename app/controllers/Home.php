<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $d = '')
    {
        echo __METHOD__;
        $model = new Model();
        $user['name'] = 'Alex';
        $result = $model->where($user);
        print_pre($result);
        $this->view('home');
    }
}
