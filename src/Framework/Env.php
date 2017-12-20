<?php

namespace SAmvc\Framework;

/**
 * Class Env  - env.json params
 * @package SAmvc\Framework
 */
class Env {
    public static function get($value)
    {
        $env = file_get_contents(PUBLIC_HTML."/.env");
        $env = json_decode($env, true);
        $arr = explode('.', $value);
        foreach ($arr as $curr)
        {
            $env = isset($env[$curr]) ? $env[$curr] : false;
        }

        return $env;
    }
}
