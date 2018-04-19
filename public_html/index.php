<?php

require(__DIR__ . '/../components/autoload.php');


$config = require(__DIR__ . '/../config/config.php');
$app = new \components\Application($config);
$app->run();
