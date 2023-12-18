<?php
/**
 * HomeController
 * @author k1k9
 */
namespace app\controllers;
class HomeController extends AbstractController
{
    public function index(){
        $this->renderView(
            view:'Home',
            head:['title' => 'Welcome']);
    }

    public function error(){
        $this->retrunErrorView();
    }
}