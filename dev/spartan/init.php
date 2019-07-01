<?php
define('ROOT', dirname(dirname(__FILE__)));
define('CONF', ROOT. '/conf');
require 'rb.php';
require 'functions.php';
require 'error_class.php';
require_or_die('conf/env.php','Fichero conf/env.php no encontrado');
R::setup(
  DB['TYPE'] . ':host=' . DB['HOST'] .  ';dbname=' . DB['NAME'],
  DB['USER'],
  DB['PASS']
);
require 'session_class.php';
require 'helpers.php';
require 'controller_class.php';
require 'model_class.php';
require 'spartan_class.php';
require 'router_class.php';
require_or_die('conf/routes.php','Fichero conf/routes.php no encontrado');
require 'init_dev.php';

Spartan\Spartan::run();