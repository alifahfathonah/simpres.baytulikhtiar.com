<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('active_link'))
{
  function single_menu($controller)
  {
    $CI     = get_instance();
    $class  = $CI->router->fetch_class();
    $method = $CI->router->fetch_method();

    return ($class.'/'.$method == $controller) ? 'active' : '';
  }

  function parent_menu($controller)
  {
    $CI     = get_instance();
    $class  = $CI->router->fetch_class();

    return ($class == $controller) ? 'active' : '';
  }

  function child_menu($controller)
  {
    $CI     = get_instance();
    $method = $CI->router->fetch_method();

    return ($method == $controller) ? 'active' : '';
  }
}
?>