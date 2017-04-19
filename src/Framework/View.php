<?php
namespace SAmvc;

class View {

    public function render($name)
    {
        require 'views/' . $name . '.php';
    }

    public function set($key,$value)
    {
      $this->$key = $value;
    }

}
