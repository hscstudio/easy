<?php
spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        $fileName = strtolower($fileName);
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    if (substr($fileName, 0, 5) == 'core/') {
        $fileName = CORE_DIR . '/' . substr($fileName, 5);
    }
    else if (substr($fileName, 0, 4) == 'app/') {
        $fileName = APP_DIR . '/' . substr($fileName, 4);  
    }
    //echo $fileName . "<br>";
    if (file_exists($fileName)) {
        require $fileName;
    } else {
        $error_message = 'File class ' . $className . ' not found!';
        require ERROR_PAGE;
        exit;
    }
});
