<?php
error_reporting(-1);

// получаем строку запроса
$query = rtrim($_SERVER['QUERY_STRING'], '/');

require_once '../vendor/core/Router.php';
require_once '../vendor/libs/functions.php';
require_once '../app/controllers/Main.php';
require_once '../app/controllers/Posts.php';
require_once '../app/controllers/PostsNew.php';


Router::add("^$", ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z]+)?$');
//Router::add('<controller>[a-z-]+>/<action>[a-z]+>'); аналог

//debug(Router::getRoutes());

Router::dispatch($query);
