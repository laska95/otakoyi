<?php

function autoloader($class){
    $file = __DIR__.'/../'.$class . '.php';
    $file = str_replace('\\', '/', $file);
    require_once  $file;
}

spl_autoload_register('autoloader');

