<?php
namespace Core;

use Core\Database;
use Core\Helper;

class Application
{
    static $config;
    public $connection;
    public $url;
    public $controller;
    public $actions;
    public $middlewares;

    public function __construct($config)
    {
        self::$config = $config;
    }

    public function database()
    {
        $config = self::$config['db'];
        $this->connection = Database::connection($config);
    }

    private function route()
    {
        $this->url = Helper::getUrl();

        // set default controllerID, $matchactionID
        $controllerID = 'site';
        $actionID = 'index';

        // clean path of URL
        $path = $this->url['path'];
        if (!empty(WEB_PATH)) {
            $path = str_replace('/' . WEB_PATH . '/', '', $path);
        } else {
            if ($path[0] == '/') {
                $path = substr($path, 1, strlen($path));
            }
        }
        if (preg_match("/\b(.*\.php)\b/i", $path, $match)):
            $path = str_replace($match[0], '', $path);
        endif;

        // get controllerID, $actionID
        $paths = explode('/', $path);
        if (count($paths) == 1) {
            if (!empty($paths[0])) {
                $controllerID = $paths[0];
            }

        } else if (count($paths) == 2) {
            if (!empty($paths[0])) {
                $controllerID = $paths[0];
                if (!empty($paths[1])) {
                    $actionID = $paths[1];
                }
            }
        }
        $this->controller['id'] = str_replace('/', '', strtolower($controllerID));
        $this->controller['action_id'] = str_replace('/', '', strtolower($actionID));
    }

    public function getMiddleware()
    {
        $list_middleware = $this->controller['object']->middleware();
        foreach($list_middleware as $middleware){
            $actions = !empty($middleware['actions'])?$middleware['actions']:[];
            $params = !empty($middleware['params'])?$middleware['params']:'';
            if(is_array($middleware)){
                $obj = new $middleware['class'](
                    $this, 
                    $actions, 
                    $params
                );
            }
            else{
                $obj = new $middleware($this);
            }
            $this->middlewares[$obj->position][] = [
                'object' => $obj,
                'actions' => $obj->getActions(),
            ]; 
        }
        
    }

    public function loadController()
    {
        $this->route();
        // get controllerClass
        $this->controller['class'] = Helper::snakeToStudlyCaps($this->controller['id']);
        // load controllerClass
        $controller = 'app\\controllers\\' . $this->controller['class'];
        $this->controller['object'] = new $controller($this);
        $this->actions = preg_grep('/^action/', get_class_methods($this->controller['object']));
        $this->getMiddleware();
    }

    public function loadAction()
    {
        $this->loadController();
        // get action function
        $action_name = 'action-' . $this->controller['action_id'];
        $this->controller['action_function'] = Helper::snakeToStudlyCaps($action_name, false);
        $action = $this->controller['action_function'];
        $this->beforeAction($this->controller['action_id']);
        if (method_exists($this->controller['object'], $action)) {
            $queries = explode('&', $this->url['query']);
            $params = [];
            foreach ($queries as $query) {
                $parts = explode('=', $query);
                $params[@$parts[0]] = @$parts[1];
            }
            $this->controller['object']->$action($params);
            $this->afterAction($this->controller['action_id']);
        } else {
            $error_message = 'action not exists';
            require ERROR_PAGE;
            exit;
        }
    }

    public function beforeAction($action)
    {
        $list_middleware = (!empty($this->middlewares['before']))?$this->middlewares['before']:[];
        foreach($list_middleware as $middleware){
            // check this middleware for this action
            // $middleware['actions'] = ['*'] ['index', '-index'] '-index', '-view'] ['*', '-index', 'view']
            $run = false;
            foreach($middleware['actions'] as $actions){
                if($actions == '*'){
                    $run = true;
                }
                else if($actions[0] == '-'){
                    $run = substr($actions,1)!=$action;
                }
                else {
                    $run = $actions==$action;
                    if ($run) break;
                }
            }
            if ($run) $middleware['object']->run();
        }
    }

    public function afterAction()
    {
        $list_middleware = (!empty($this->middlewares['after']))?$this->middlewares['after']:[];
        foreach($list_middleware as $middleware){
            $run = false;
            foreach($middleware['actions'] as $actions){
                if($actions == '*'){
                    $run = true;
                }
                else if($actions[0] == '-'){
                    $run = substr($actions,1)!=$action;
                }
                else {
                    $run = $actions==$action;
                    if ($run) break;
                }
            }
            if ($run) $middleware['object']->run();
        }
    }

    public function run()
    {
        $this->database();
        $this->loadAction();
    }

}
