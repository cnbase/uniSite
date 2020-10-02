<?php
return [
    [
        'method'    =>  'post',
        'path'      =>  '/api/check_login',
        'callback'  =>  function(){
            (new \admin\controller\Pub())->check_login();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/login',
        'callback'  =>  function(){
            (new \admin\controller\Pub())->login();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/logout',
        'callback'  =>  function(){
            (new \admin\controller\User())->logout();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/change_password',
        'callback'  =>  function(){
            (new \admin\controller\User())->change_password();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/index',
        'callback'  =>  function(){
            (new \admin\controller\Index())->index();
        },
        'isRegular' =>  false
    ]
];