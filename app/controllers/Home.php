<?php

class Home extends Controller
{
    public function index($a = '', $b = '', $d = '')
    {
        $model = new Model();
        $user['name'] = 'Chris';
        $user['age'] = 62;

        $result = $model->update(1, $user);

        print_pre($result);
        $this->view('home');
    }
}
