<?php
namespace SAmvc;

class Session
{

  public static function init()
  {
    if (session_status() == PHP_SESSION_NONE) {
      @session_start();
    }
  }

  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public static function get($key)
  {
    if (isset($_SESSION[$key]))
    return $_SESSION[$key];
    else
    return false;
  }

  public static function remove($key)
  {
    unset($_SESSION[$key]);
  }

  public static function destroy()
  {
    session_destroy();
  }

}
