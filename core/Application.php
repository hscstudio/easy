<?php
namespace core;

use core\Database;
use core\Helper;

class Application
{
  static $config;
  public $connection;
  public $url;
  public $controller;

  function __construct($config){
    self::$config = $config;
  }

  public function database(){
    $config = self::$config['db'];
    $this->connection = Database::connection($config, self::$config['debug']);
  }

  private function route(){
    $this->url = Helper::getUrl();

    // set default controllerID, $matchactionID
    $controllerID = 'site';
    $actionID = 'index';

    // clean path of URL
    $path = $this->url['path'];
    $web_path = self::$config['web_path'];
    if(!empty($web_path)){
      $path = str_replace('/'.$web_path.'/', '', $path);
    }
    else{
      if($path[0]=='/'){
        $path = substr($path,1,strlen($path));
      }
    }
    if (preg_match("/\b(.*\.php)\b/i", $path, $match)) :
      $path = str_replace($match[0], '', $path);
    endif;

    // get controllerID, $actionID
    $paths = explode('/', $path);
    if(count($paths)==1){      
      if(!empty($paths[0])) $controllerID = $paths[0];
    }
    else if(count($paths)==2){
      if(!empty($paths[0])){
        $controllerID = $paths[0];
        if(!empty($paths[1])) $actionID = $paths[1];
      }
    }
    $this->controller['id'] = str_replace('/', '', strtolower($controllerID));
    $this->controller['action_id'] = str_replace('/', '', strtolower($actionID));
  }

  public function loadController(){
    $this->route();
    // get controllerClass
    $this->controller['class'] = Helper::snakeToStudlyCaps($this->controller['id']);
    // load controllerClass
    $controller = 'app\\controllers\\'.$this->controller['class'];
    $this->controller['object'] = new $controller(self::$config, $this); 
  }

  public function loadAction(){
    $this->loadController();
    // get action function
    $action_name = 'action-'.$this->controller['action_id'];
    $this->controller['action_function'] = Helper::snakeToStudlyCaps($action_name, false);
    $action = $this->controller['action_function'];
    if (method_exists($this->controller['object'],$action)){
      $queries = explode('&',$this->url['query']);
      $params = [];
      foreach($queries as $query){
        $parts = explode('=',$query);
        $params[@$parts[0]] = @$parts[1];
      }
      $this->controller['object']->$action($params);
    }
    else{
      $error_message = 'action not exists';
      require ERROR_PAGE;
      exit; 
    }
  }

  public function run(){
    $this->database();
    $this->loadAction();
  }

}