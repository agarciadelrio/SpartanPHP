<?php
use Spartan\ErrorClass;
/*
function __autoload($class) {
  if(substr($class,-10) ===  'Controller') {
    $class = strtolower(str_replace("\\", "/", substr($class,0,-10) )) . '_controller.php';
  } else {
    $class = strtolower(str_replace("\\", "/", $class)) . '.php';
  }
  if(file_exists($class)) {
    require_once $class;
  } else {
    echo "$class no encontrado."; die;
  }
}
*/
function require_or_die($file, $msg) {
  if(!file_exists($file)) {
    ErrorClass::die($msg);
  } else {
    require $file;
  }
}