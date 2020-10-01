<?php
require __DIR__.'/vendor/autoload.php';

(new uniPHP([
    'entryFile' =>  'index.php',
    'WEB_DIR'   =>  __DIR__.'/www',
    'APP_DIR'   =>  __DIR__.'/app',
    'CONF_DIR'  =>  __DIR__.'/config',
    'ROUTE_DIR' =>  __DIR__.'/route',
    'MODULE_NAME'   =>  'index'
]))->run();