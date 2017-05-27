<?php

namespace SAmvc\Services;

use SAmvc\Framework\Env;

/**
 * Class Redirect
 * @package SAmvc\Services
 */
class Redirect {

    /**
     * @param $page
     * @param $qry
     * @param array $data
     */
    public static function to($page, $qry, $data = array())
    {
        unset($qry['url']);
        foreach ($data as $key => $value)
        {
            $qry[$key] = $value;
        }
        $qry = http_build_query($qry);
        header('location:'.Env::get('url').$page.'?'.$qry);
    }

    /**
     * redirect back
     */
    public static function back()
    {
        header("location:javascript://history.go(-1)");
    }

}
