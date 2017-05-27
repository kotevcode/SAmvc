<?php

namespace SAmvc\Framework;

/**
 * Class View
 * @package SAmvc\Framework
 */
class View {
    /**
     * @param $name
     * @param bool $next
     */
    public function render($name, $next = false)
    {
        require 'views/'.$name.'.php';
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->$key = $value;
    }

}
