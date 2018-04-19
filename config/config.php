<?php

$urlManager = [
    'class' => '\components\UrlMenedger',
    'params' => [
        'rules' => [
            "<CTRL>" => "<CTRL>/<ACT=index>",
            "<CTRL>/<ACT>" => "<CTRL>/<ACT>",
            "api/<CTRL>/<ACT>" => "<CTRL>/<ACT>",
        ]
    ]
    
];

$config = [
    'urlManager' => $urlManager
    
];

return $config;
