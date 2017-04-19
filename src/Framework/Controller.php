<?php
namespace SAmvc\Framework;

class Controller {

  function __construct() {
    Session::init();
    $this->view = new View();
  }


}
