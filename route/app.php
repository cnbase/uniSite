<?php
return [
    [
        'method'    =>  'get',
        'path'      =>  '/',
        'callback'  =>  function(){
            echo 'hello uniPHP.';
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'get',
        'path'      =>  '/demo',
        'callback'  =>  function(){
            echo 'hello demo.';
        },
        'isRegular' =>  false
    ]
];