<?php
namespace Spartan;

class SessionClass {
  public static function init() {
    session_start([
      'cookie_lifetime' => 86400,
    ]);
  }

  public static function checkUser() {
    //print_r($_SESSION); die;
    //return true;
    if(isset($_SESSION['current_user'])) {
      $user = \R::load('user', $_SESSION['current_user']);
      if($user->id!==0) return $user;
    }

    ControllerClass::json([
      'msg' => 'ERROR',
      'user' => 'Usuario inválido'
    ]);
  }
}