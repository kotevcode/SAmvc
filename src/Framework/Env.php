<?php
namespace SAmvc\Framework;
/**
 * env.json params
 */
class Env
{
  public static function get($value)
  {
    $env = file_get_contents(PUBLIC_HTML."/.env");
    $env = json_decode($env, true);
    $arr = explode('.',$value);
    foreach ($arr as $curr) {
      $env = $env[$curr];
    }
    return $env;
  }
}
