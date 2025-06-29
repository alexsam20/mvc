<?php

class Login
{
    use Controller;

    public function index()
    {
        $data = [];
        if ($this->isPost()) {
            $user = new User();
            $arr['email'] = $_POST['email'];

            $row = $user->first($arr);

            if ($row) {
                if ($row->password === $_POST['password']) {
                    $_SESSION['user'] = $row;
                    redirect('home');
                }
            }
            $user->errors['email'] = 'Wrong email or password';
            $data['errors'] = $user->errors;
        }

        $this->view('login', $data);
    }
}