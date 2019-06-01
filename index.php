<?php
define('PATH', __DIR__);
require PATH.'/core/router.php';

$router = new Router;
$router->run($_GET['url']);
?>
