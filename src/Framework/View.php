<?php
namespace SAmvc\Framework;

class View {

    public function render($name, $next = false)
    {
        require 'views/' . $name . '.php';
    }

    public function set($key,$value)
    {
      $this->$key = $value;
    }

}
