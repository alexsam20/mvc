<?php
namespace Models;
use Model\Model;

defined('ROOTPATH') OR exit('Access Denied!');
class User
{
    use Model;

    protected string $table = 'users';

    protected array $allowedFields = [
        'email', 'password',
    ];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['email'])) {
            $this->errors['email'] = 'Email is required';
        } else
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email';
        }
        if (empty($data['password'])) {
            $this->errors['password'] = 'Password is required';
        }
        if (empty($data['terms'])) {
            $this->errors['terms'] = 'Please accept terms and conditions';
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }
}