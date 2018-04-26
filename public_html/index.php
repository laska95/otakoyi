<?php

require(__DIR__ . '/../components/autoload.php');

$config = require(__DIR__ . '/../config/config.php');

use \components\Main as Main;

//$db = new $config['dataBase']['class']($config['dataBase']);
//$db->tryConnect();


Main::$app = new \components\Application($config);
Main::$app->run();
