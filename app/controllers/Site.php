<?php
namespace app\controllers;

use core\Controller;

class Site extends Controller
{
  public $auth = false;

  public function actionIndex(){
    $this->render([
      'index'
    ]);
  }

}