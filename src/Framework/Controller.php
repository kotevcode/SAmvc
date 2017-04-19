<?php
namespace SAmvc;

class Controller {

  function __construct() {
    Session::init();
    $this->view = new View();
  }


}
