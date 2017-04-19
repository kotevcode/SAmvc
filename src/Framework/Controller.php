<?php
namespace SAmvc\Framework;

use SAmvc\Services\Session;

class Controller {

  function __construct() {
    Session::init();
    $this->view = new View();
  }


}
