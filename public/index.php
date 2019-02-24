<?php
$production = false;
require_once __DIR__ . "/../app/config.php";
$application = new core\Application($config);
$application->run();