<?php

namespace vendor\core;

class Router
{
    protected static $routes = []; // массив из таблиц маршрутов
    protected static $route = []; // текущий маршрут, который должен отработать

    // метод, который добавляет в таблицу маршрутов эти самые маршруты
    // 1 параметр регулярное выражение как ключ массива, 2 параметр какой контроллер вызвать
    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    // метод, возвращает текущий маршрут
    public static function getRoute()
    {
        return self::$route;
    }


    /**
     * ищет совпадение с запросом в таблице маршрутов, если найдено, записывается в текущий маршрут
     * @param $url
     * @return bool
     */
    public static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * перенаправляет URL корректному адресу
     * @param $url
     * @return void
     */
    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);
        var_dump($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['controller'];
            debug(self::$route);
            if (class_exists($controller)) {
                $controlObject = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($controlObject, $action)) {
                    $controlObject->$action();
                } else {
                    echo "Method <b>$controller::$action</b> not found";
                }
            } else {
                echo "Controller <b>$controller</b> not found";
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }

    /**
     * заменяет у строки - на ' ', приводит первые символы к верхнему регистру и затем склеивает
     * @param $name
     * @return string|string[]
     */
    protected static function upperCamelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

    }

    /**
     * приводит первую букву к нижнему регистру
     * @param $name
     * @return string|string[]
     */
    protected static function lowerCamelCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }

    /**
     * обрезает возможные GET параметры
     * @param $url
     */
    protected static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }
    }


}