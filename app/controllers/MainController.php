<?php

namespace app\controllers;


class MainController extends AppController
{
    public $layout = 'main';

    public function indexAction()
    {
//        $this->layout = false;
//        $this->layout = 'default';
//        $this->view = 'test';
        $name = 'Maksim';
        $hi = 'hello';
        $this->set(compact('name', 'hi'));
    }

    public function testAction()
    {

    }
    public function testPageAction()
    {

    }
}