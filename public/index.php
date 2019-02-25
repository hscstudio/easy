<?php
$production = false;
require_once __DIR__ . "/../app/config.php";
$application = new Core\Application($config);
$application->run();