<?php
namespace Spartan;

class ErrorClass {
  public static function die($msg) {
    echo $msg; die;
  }
}