<?php 
define('PATH_FRONT', __DIR__);
require PATH_FRONT.'/vendor/autoload.php';
use Routes\App;
$app = new App();
$app->init();
?>