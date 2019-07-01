<?php
namespace Spartan;

class ControllerClass {

  public static function render($view, $params=[]) {
    ob_start();
    header('Content-Type: text/html; charset=UTF-8');
    extract($params);
    $GLOBALS += $params;
    include "views/$view.php";
    $yield_body = ob_get_clean();
    echo $yield_body;
    die;
  }

  public static function json($params=[], $status=null) {
    ob_clean();
    header('content-type: text/json; charset=UTF-8');
    if($status) {
      http_response_code($status);
    }
    echo json_encode($params, JSON_NUMERIC_CHECK);
    die;
  }

  public static function redirect($url='/', $params=[]) {
    if($params!=[]) {
      $_SESSION = array_merge($_SESSION, $params);
    }
    ob_clean();
    header("Location: $url");
    die;
  }

  public static function _404_($params) {
    self::render('errors/404', $params);
  }

  public static function _500_($params) {
    self::render('errors/500', $params);
  }

  public static function goBack() {
    ob_clean();
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
    die;
  }

}