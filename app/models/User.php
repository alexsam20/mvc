<?php

class User
{
    use Model;

    protected string $table = 'users';

    protected array $allowedFields = [
        'name', 'age',
    ];
}