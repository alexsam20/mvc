<?php
namespace Core;
defined('ROOTPATH') OR exit('Access Denied!');
class Session
{
    public const string APP_SESSION_KEY = 'APP';
    public const string APP_USER_KEY = 'USER';

    /** activate session if not yet started **/
    private function startSession(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return 1;
    }

    /** Put data into the session **/
    public function set(mixed $keyOrArray, mixed $value = ''): int
    {
        $this->startSession();
        if (is_array($keyOrArray)) {
            foreach ($keyOrArray as $key => $value) {
                $_SESSION[self::APP_SESSION_KEY][$key] = $value;
            }

            return 1;
        }

        $_SESSION[self::APP_SESSION_KEY][$keyOrArray] = $value;

        return 1;
    }

    /** Get data from the session. Default is return if data not found **/
    public function get(string $key, mixed $default = ''): mixed
    {
        $this->startSession();
        if (isset($_SESSION[self::APP_SESSION_KEY][$key])) {
            return $_SESSION[self::APP_SESSION_KEY][$key];
        }
        return $default;
    }

    /** Save the user row data into the session after a login **/
    public function auth(mixed $userRow): int
    {
        $this->startSession();
        $_SESSION[self::APP_USER_KEY] = $userRow;

        return 0;
    }

    /** Remove user data from the session */
    public function logout(): int
    {
        $this->startSession();
        if (empty($_SESSION[self::APP_USER_KEY])) {
            unset($_SESSION[self::APP_USER_KEY]);
        }

        return 0;
    }

    /** Check if user is logged in */
    public function isLoggedIn(): bool
    {
        $this->startSession();
        if (!empty($_SESSION[self::APP_USER_KEY])) {
            return true;
        }

        return false;
    }

    /** Check if user is an admin */
    /*public function isAdmin(): bool
    {
        $this->startSession();
        $db = new \Core\Database();

        if (!empty($_SESSION[self::APP_USER_KEY])) {
            $arr = [];
            $arr['id'] = $_SESSION[self::APP_USER_KEY]->role_id;

            if ($db->getRow("select * from auth_roles where id = :id && role = 'admin' limit 1", $arr)) {
                return true;
            }
        }

        return false;
    }*/

    /** Get data from a column in the session user data */
    public function user(string $key = '', mixed $default = ''): mixed
    {
        $this->startSession();
        if (empty($key) && !empty($_SESSION[self::APP_USER_KEY])) {
            return $_SESSION[self::APP_USER_KEY];
        } else
            if (!empty($_SESSION[self::APP_USER_KEY]->$key)) {
                return $_SESSION[self::APP_USER_KEY]->$key;
            }

        return $default;
    }

    /** Return data from a key and deletes it **/
    public function pop(string $key, mixed $default = ''): mixed
    {
        $this->startSession();
        if (!empty($_SESSION[self::APP_SESSION_KEY][$key])) {
            $value = $_SESSION[self::APP_SESSION_KEY][$key];
            unset($_SESSION[self::APP_SESSION_KEY][$key]);

            return $value;
        }

        return $default;
    }

    /** Returns all data from the APP array */
    public function all(): mixed
    {
        $this->startSession();

        if (isset($_SESSION[self::APP_SESSION_KEY])) {
            return $_SESSION[self::APP_SESSION_KEY];
        }

        return [];
    }
}