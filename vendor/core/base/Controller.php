<?php


namespace vendor\core\base;


abstract class Controller
{
    /**
     * текущий маршрут и параметы (controller, action, params)
     * @var
     */
    public $route = [];

    /**
     * текущий шаблон
     * @var string
     */
    public $layout;

    /**
     * вид
     * @var
     */
    public $view;

    /**
     * пользовательские данные
     * @var
     */
    public $vars = [];

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    public function getView()
    {
        $viewObject = new View($this->route, $this->layout, $this->view);
        $viewObject->render($this->vars);
    }

    public function set($vars)
    {
    $this->vars = $vars;
    }
}