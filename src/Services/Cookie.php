<?php

namespace SAmvc\Services;

/**
 * Class Cookie
 * @package SAmvc\Services
 */
class Cookie {
    /**
     * @param $key
     * @param $value
     * @param bool $expire
     * @return bool
     */
    public static function set($key, $value, $expire = false)
    {
        if ($expire)
        {
            return setcookie($key, $value, $expire);
        } else
        {
            return setcookie($key, $value, time() + 10 * 365 * 24 * 60 * 60, "/");
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        if (isset($_COOKIE[$key]))
        {
            return $_COOKIE[$key];
        } else
        {
            return false;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        if (isset($_COOKIE[$key]))
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function remove($key)
    {
        if (isset($_COOKIE[$key]))
        {
            unset($_COOKIE[$key]);

            return setcookie($key, '', time() - 3600, '/');
        } else
        {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function destroy()
    {
        foreach ($_COOKIE as $key => $value)
        {
            setcookie($key, $value, time() - 3600, '/');
        }

        return true;
    }
}
