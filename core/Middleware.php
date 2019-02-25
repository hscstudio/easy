<?php
namespace Core;

abstract class Middleware
{ 
    public $position = 'before'; // before or action 
    static $application;
    /**
     * ['*'] all action 
     * ['index', 'view'] index and view action only
     * ['*', '-view'] all action except view
     */
    static $actions;
    static $params;

    public function __construct($application, $actions, $params)
    {
        static::$application = $application;
        if(!empty($actions)) static::$actions = $actions;
        if(!empty($params)) static::$params = $params;
    }

    public function getActions()
    {
        return static::$actions;
    }
    
    abstract public function run();
}
