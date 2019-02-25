<?php
namespace Core;

class Authentication extends Middleware
{ 
    public $position = 'before';
    static $application;
    static $actions = ['*'];
    static $params = [
        'except' => ['login']
    ];

    public function run()
    {
        $controller = static::$application->controller;
        if (!empty($controller['object']->getUser())) {

        } else {
            if (in_array($controller['action_id'], static::$params['except'])) {

            } else {
                $_SESSION['redirect'] = 'ok';
                $controller['action_id'] = 'login';
                $controller['object']->redirect('site/login');
            }

            if (!empty($_SESSION['redirect'])) {
                unset($_SESSION['redirect']);
                $error_message = 'Too many redirect';
                require ERROR_PAGE;
                exit;
            }
        }
    }
}
