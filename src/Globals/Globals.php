<?php

namespace Globals;

/*
class Globals
{
    public ?array $SERVER = null;
    public ?array $COOKIE = null;
    public ?array $GET = null;
    public ?array $FILES = null;
    public ?array $POST = null;
    public ?array $SESSION = null;

    public function __construct()
    {
        $this->SERVER = array_map('htmlspecialchars', $_SERVER);
        $this->GET = array_map('htmlspecialchars', $_GET);
        $this->FILES = array_map('htmlspecialchars', $_FILES);
        $this->POST = array_map('htmlspecialchars', $_POST);
        $this->COOKIE = array_map('htmlspecialchars', $_COOKIE);
        $this->SESSION = array_map('htmlspecialchars', $_SESSION['user']);
    }
}
*/

class Globals
{
    private $GET;
    private $POST;

    public function __construct()
    {
        $this->GET = filter_input_array(INPUT_GET) ?? null;
        $this->POST = filter_input_array(INPUT_POST) ?? null;
        $this->SERVER = filter_input_array(INPUT_SERVER) ?? null;
        $this->COOKIE = filter_input_array(INPUT_COOKIE) ?? null;
        $this->ENV = filter_input_array(INPUT_ENV) ?? null;
        $this->SESSION = $_SESSION ?? null;
    }

    public function getGET($key = null)
    {
        if (null !== $key) {
            return $this->GET[$key] ?? null;
        }
        return $this->GET;
    }

    public function getPOST($key = null)
    {
        if ($key !== null) {
            return $this->POST[$key] ?? null;
        }
        return $this->POST;
    }

    public function getSERVER($key = null)
    {
        if ($key !== null) {
            return $this->SERVER[$key] ?? null;
        }
        return $this->SERVER;
    }

    public function getCOOKIE($key = null)
    {
        if ($key !== null) {
            return $this->COOKIE[$key] ?? null;
        }
        return $this->COOKIE;
    }

    public function getENV($key = null)
    {
        if ($key !== null) {
            return $this->ENV[$key] ?? null;
        }
        return $this->ENV;
    }

    public function getSESSION($key = null)
    {
        if ($key !== null) {
            return $this->SESSION[$key] ?? null;
        }
        return $this->SESSION;
    }
}
