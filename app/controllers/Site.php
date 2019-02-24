<?php
namespace App\Controllers;

use Core\Controller;

class Site extends Controller
{
    public $auth = false;

    public function actionIndex()
    {
        $this->render([
            'index',
        ]);
    }

}
