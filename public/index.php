<?php
error_reporting(-1);

use vendor\core\Router;


// получаем строку запроса
$query = rtrim($_SERVER['QUERY_STRING'], '/');

define('WWW', __DIR__);
define('CORE', dirname(__DIR__) . '/vendor/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('LAYOUT', 'default');

require_once '../vendor/core/Router.php';
require_once '../vendor/libs/functions.php';

spl_autoload_register(function ($class) {
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

// my routs
Router::add("^page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$", ['controller' => 'PageController']);
Router::add("^page/(?P<alias>[a-z-]+)$", ['controller' => 'PageController','action' => 'view']);

// default routs
Router::add("^$", ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
//Router::add('<controller>[a-z-]+>/<action>[a-z]+>'); аналог

Router::dispatch($query);
