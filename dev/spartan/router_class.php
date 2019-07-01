<?php
namespace Spartan;

use App\Controllers;

class RouterClass {
  public static $GET = [];
  public static $POST = [];

  public static function preparePath($route) {
    $out = "/$route";
    $out = str_replace('*', '(.*)', $out);
    $out = str_replace('/', '\/', $out);
    return '/^' . $out . '$/';
  }

  public static function matchPath() {
    $path_info = '/' . trim(strtok(($_SERVER["REQUEST_URI"] ?? ''),'?'),'/');
    $method = $_SERVER['REQUEST_METHOD'];
    foreach(self::$$method as $patt => $action) {
      $match = [];
      preg_match($patt, $path_info, $match);
      if(count($match)>0) {
        $params = [];
        $i=1;
        foreach($action[1] as $vars) {
          $params[$vars] = $match[$i++];
        }
        $controller = explode('::', $action[0])[0];
        $controller = strtolower( str_replace('Controller','_controller', $controller) );
        $controller = "app/controllers/$controller.php";
        if(file_exists($controller)) {
          require_once($controller);
        }
        $GLOBALS['ControllerAction'] = str_replace('Controller','', $action[0]);
        //print_r($action); print_r($controller); die;
        call_user_func("App\\Controllers\\" . $action[0], $params); die;
        echo $controller; die;
        return [$action[0], $params];
        break;
      }
    }
    return ['404',[]];
  }

  public static function GET($route, $controller, $var_names=[]) {
    $new_path = self::preparePath($route);
    self::$GET[$new_path] = [$controller, $var_names];
  }

  public static function POST($route, $controller, $var_names=[]) {
    $new_path = self::preparePath($route);
    self::$POST[$new_path] = [$controller, $var_names];
  }

  public static function RESOURCE($resource, $controller) {
    self::GET(  $resource,           "$controller::index");
    self::GET( "$resource/new",      "$controller::new");
    self::GET( "$resource/*/edit",   "$controller::edit", ['id']);
    self::GET( "$resource/*",        "$controller::show", ['id']);
    self::POST( $resource,           "$controller::create");
    self::POST("$resource/*/delete", "$controller::delete", ['id']);
    self::POST("$resource/*",        "$controller::update", ['id']);
  }

  public static function HASMANY($resource, $subset, $controller) {
    self::GET( "$resource/*/$subset",          "$controller::{$subset}_index",  ['id']);
    self::GET( "$resource/*/$subset/new",      "$controller::{$subset}_new",    ['id']);
    self::GET( "$resource/*/$subset/*/edit",   "$controller::{$subset}_edit",   ['id', 'child_id']);
    self::GET( "$resource/*/$subset/*",        "$controller::{$subset}_show",   ['id', 'child_id']);
    self::POST("$resource/*/$subset",          "$controller::{$subset}_create", ['id']);
    self::POST("$resource/*/$subset/*/delete", "$controller::{$subset}_delete", ['id', 'child_id']);
    self::POST("$resource/*/$subset/*",        "$controller::{$subset}_update", ['id', 'child_id']);
  }

  public static function info() {
    ControllerClass::json([
      //'match' => self::matchPath(),
      'GET'   => self::$GET,
      'POST'  => self::$POST,
      '_SERVER'  => $_SERVER,
    ]);
  }

}