<?php

namespace SAmvc\Services;

/**
 * Class Session
 * @package SAmvc\Services
 */
class Session {
    /**
     * initialize the session
     */
    public static function init()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            @session_start();
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        } else
        {
            return false;
        }
    }

    /**
     * @param $key
     */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * destroy the session
     */
    public static function destroy()
    {
        session_destroy();
    }

}
