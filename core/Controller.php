<?php
namespace core;

use core\Helper;
use core\User;

class Controller
{
  static $config;
  public $app;
  public $auth=false;
  public $layout = "main";
  public $layoutFile;
  public $view;
  public $viewFile;
  public $js_header;

  public function __construct($config, $app){
    self::$config = $config;
    $this->app = $app;
  }

  public function getLayout(){
    $layoutFile = self::$config['dir_root'] . '/app/views/layouts/' . $this->layout . '.php';
    if(file_exists($layoutFile)){
      $this->layoutFile = $layoutFile;
    }
    else{
      $error_message = 'file layout not found in '.$layoutFile;
      require ERROR_PAGE;
      exit;
    }
  }

  public function getView($view){
    $viewFile = self::$config['dir_root'] . '/app/views/'.$this->app->controller['id'].'/' . $view . '.php';
    if(file_exists($viewFile)){
      $this->viewFile = $viewFile;
    }
    else{
      $viewFile = self::$config['dir_root'] . '/app/views/default/' . $view . '.php';
      if(file_exists($viewFile)){
        $this->viewFile = $viewFile;
      }
      else{ 
        $error_message = 'file view not found in '.$viewFile;
        require ERROR_PAGE;
        exit;
      }
    }
  }

  public function beforeAction(){
    if($this->auth){
      if (!empty($this->getUser())){

      }
      else{
        if (in_array($this->app->controller['action_id'],['login','logout'])){

        }
        else{
          $_SESSION['redirect'] = 'ok';
          $this->app->controller['action_id'] = 'login';
          $this->redirect('site/login');
        }
        
        if (!empty($_SESSION['redirect'])){
          unset($_SESSION['redirect']);
          $error_message = 'Too many redirect';
          require ERROR_PAGE;
          exit;
        }
      }
    }
  }

  public function redirect($target, $params=[]){
    $redirect = BASE_URL;
    if(strpos('http',$target)>0){
      
    }
    else{
      // ['site/index']
      if(strpos($target, '/')>0){
        $redirect .= '/' . $target;
      }
      // ['index']
      else{
        $redirect .= $this->app->controller['id'] . '/' . $target;
      }
      if(!empty($params)) $redirect .= '?' . http_build_query($params);
    }
    header('Location: '.$redirect); /* Redirect browser */
    exit;
  }

  public function render($object){
    $this->beforeAction();
    if(is_array($object)){
      $this->getView($object[0]);
      ob_start();
      if(!empty($object[1])){
        foreach($object[1] as $num => $var){
          ${$num} = $var;
        }
      }
      $controller = $this;
      require_once($this->viewFile);
      $content = ob_get_contents();
      ob_end_clean();
      $js_header = $this->js_header;
      $this->getLayout();
      require_once($this->layoutFile);
    }
    else{
      if(is_string($object)){
        echo $object;
      }
      else{
        require self::$config['dir_root'] . '/core/' . '404.php';
        exit; 
      }
    }  
  }

  public function actionLogin(){
    if(!empty($_POST)){
      $username = Helper::safeText($_POST['username']);
      $password = $_POST['password'];
      $user = User::findByUsername($this->app->connection, $username);
      if($user){
        $valid = $user->verifyPassword($password);
        if($valid){
          $user->set($user);
          $this->setMessage('success', 'Login successfull');
          $this->redirect('site/index');
        }
        else{
          $this->setMessage('warning', 'Password not match');
        }
      }
      else{
        $this->setMessage('warning', 'Login fail!');
      }
    }

    $this->render([
      'login'
    ]);
  }

  public function actionLogout(){
    User::set();
    $this->setMessage('success', 'Logout success');
    $this->redirect('site/index');
  }

  public function setMessage($status, $text){
    $message = [
      'status' => $status,
      'text' => $text,
    ];
    $_SESSION['message'] = $message;
  }

  public function getMessage(){
    $return = '';
    if(!empty($_SESSION['message'])){
      $message = $_SESSION['message'];
      $return = '<div class="notification is-'.$message['status'].'">'.
        $message['text']
        .'</div>';
      unset($_SESSION['message']);      
    }
    return $return;
  }

  public function getUser(){
    return User::get();
  }
}