<?php

namespace SAmvc\Framework;

/**
 * Class View
 * @package SAmvc\Framework
 */
class View {
    /**
     * @param $name
     * @param array $compact
     */
    public function render($name, $compact = [])
    {
        extract($compact);
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
