<?php

$urlManager = [
    'class' => '\components\UrlMenedger',
    'params' => [
        'rules' => [
            "" => "<CTRL=default>/<ACT=index>",
            "<CTRL>" => "<CTRL>/<ACT=index>",
            "<CTRL>/<ACT>" => "<CTRL>/<ACT>",
            "___/<CTRL>/<ACT>" => "<CTRL>/<ACT>",
        ]
    ]
    
];

$config = [
    'urlManager' => $urlManager
    
];

return $config;
