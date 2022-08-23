<?php

namespace Globals;

class Globals
{
    private $GET;
    private $POST;
    public ?array $FILES = null;

    public function __construct()
    {
        $this->GET = filter_input_array(INPUT_GET) ?? null;
        $this->POST = filter_input_array(INPUT_POST) ?? null;
        $this->SERVER = filter_input_array(INPUT_SERVER) ?? null;
        $this->COOKIE = filter_input_array(INPUT_COOKIE) ?? null;
        $this->ENV = filter_input_array(INPUT_ENV) ?? null;
        $this->SESSION = $_SESSION ?? null;
        $this->FILES = $_FILES ?? null;
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

    public function getFILES($key = null)
    {
        if ($key !== null) {
            return $this->FILES[$key] ?? null;
        }
        return $this->FILES;
    }
}
