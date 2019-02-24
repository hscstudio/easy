<?php
session_start();
$config = [];

/* START EDIT HERE */
$config['db'] = [
  'dsn' => 'mysql:host=mysql;port=3306;dbname=easydb',
  'username' => 'root',
  'password' => 'root',
  'charset' => 'utf8',
];
/* END EDIT HERE */

/* START EDIT HERE 
Generally, mount point of application in web root, but You can place Your application 
in subdirectory, for example: http://localhost/your_project, so You can add `your_project`
in web_path config
$config['web_path'] = "your_project"; 
*/
$config['web_path'] = ""; 
/* END EDIT HERE */

$config['debug'] = !$production;
if(!$production){
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

$config['dir_root'] = __DIR__ . '/..';
$config['web_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
'://'.$_SERVER['HTTP_HOST'];

$base_url = $config['web_url'] . (!empty($config['web_path'])?'/'.$config['web_path']:'');
define('BASE_URL', $base_url);
define('ERROR_PAGE', $config['dir_root'] . '/core/' . 'error.php');

spl_autoload_register( function ($className) {
  $dir_root = __DIR__ . '/..';
  $className = ltrim($className, '\\');
  $fileName  = '';
  $namespace = '';
  if ($lastNsPos = strrpos($className, '\\')) {
      $namespace = substr($className, 0, $lastNsPos);
      $className = substr($className, $lastNsPos + 1);
      $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
  }
  $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
  $fileName = $dir_root . '/' . $fileName;
  //echo $fileName . "<br>";
  if(file_exists($fileName)){
    require $fileName;
  }
  else{
    $error_message = 'File class '.$className.' not found!';
    require ERROR_PAGE;
    exit;
  }
});