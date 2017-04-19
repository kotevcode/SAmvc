<?php
namespace SAmvc\Services;

use SAmvc\Env;
/**
 *
 */
class Redirect
{

  // redirect
  public static function to($page,$qry,$data=array()){
    unset($qry['url']);
    foreach ($data as $key => $value) {
      $qry[$key] = $value;
    }
    $qry = http_build_query($qry);
    header('location:'.Env::get('url').$page.'?'.$qry);
  }

  public static function back()
  {
    header("location:javascript://history.go(-1)");
  }

}
