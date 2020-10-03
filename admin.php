<?php
require __DIR__.'/vendor/autoload.php';
//载入公共函数
require_once __DIR__.'/config/functions.php';
uniPHP::instance([
    'entryFile' =>  'admin.php',
    'ROOT_DIR'  =>  __DIR__,
    'WEB_DIR'   =>  __DIR__.'/www/admin',
    'APP_DIR'   =>  __DIR__.'/app',
    'CONF_DIR'  =>  __DIR__.'/config',
    'ROUTE_DIR' =>  __DIR__.'/route',
    'MODULE_NAME'   =>  'admin',
])->run();