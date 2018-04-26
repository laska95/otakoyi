<?php

$urlManager = [
    'class' => '\components\UrlMenedger',
    'params' => [
        'rules' => [
            "" => "<CTRL=default>/<ACT=index>",
            "<CTRL>" => "<CTRL>/<ACT=index>",
            "<CTRL>/<ACT>" => "<CTRL>/<ACT>",
            "___/<CTRL>/<ACT>" => "<CTRL>/<ACT>",
            "<CTRL>/<ACT>/p<PGN>" => "<CTRL>/<ACT>",
            "<CTRL>/<ACT>/i<ID>" => "<CTRL>/<ACT>",
            "<CTRL>/<ACT>/p<PGN>/i<ID>" => "<CTRL>/<ACT>",
            "<CTRL>/<ACT>/i<ID>/p<PGN>" => "<CTRL>/<ACT>",
        ]
    ]
    
];

$config = [
    'urlManager' => $urlManager,
    'dataBase' => require(__DIR__ . '/db.php')
];

return $config;
