<?php


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

    // ищет совпадение с запросом в таблице маршрутов, если найдено, записывается в текущий маршрут
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
                self::$route = $route;
                debug($route);
                return true;
            }
        }
        return false;
    }

    // перенаправляет URL по корректному маршруту
    public static function dispatch($url)
    {
        if (self::matchRoute($url)) {
            $controller = self::upperCamelCase(self::$route['controller']);
            if (class_exists($controller)) {
                $controlObject = new $controller;
                $action = self::lowerCamelCase(self::$route['action']);
                debug($action);
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

    protected static function upperCamelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

    }

    protected static function lowerCamelCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }

}