<?php
namespace Spartan;

class Spartan {
  public static function run() {
    SessionClass::init();
    //RouterClass::info();
    RouterClass::matchPath();
  }
}