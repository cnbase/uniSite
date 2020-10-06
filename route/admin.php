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
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/menu',
        'callback'  =>  function(){
            (new \admin\controller\Menu())->index();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/edit_menu',
        'callback'  =>  function(){
            (new \admin\controller\Menu())->edit_menu();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/remove_menu',
        'callback'  =>  function(){
            (new \admin\controller\Menu())->remove_menu();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/auth',
        'callback'  =>  function(){
            (new \admin\controller\Auth())->index();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/edit_auth',
        'callback'  =>  function(){
            (new \admin\controller\Auth())->edit_auth();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/remove_auth',
        'callback'  =>  function(){
            (new \admin\controller\Auth())->remove_auth();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/role',
        'callback'  =>  function(){
            (new \admin\controller\Role())->index();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/edit_role',
        'callback'  =>  function(){
            (new \admin\controller\Role())->edit_role();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/remove_role',
        'callback'  =>  function(){
            (new \admin\controller\Role())->remove_role();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/role_auth',
        'callback'  =>  function(){
            (new \admin\controller\RoleAuth())->index();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/role_auth_save',
        'callback'  =>  function(){
            (new \admin\controller\RoleAuth())->save();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/role_menu',
        'callback'  =>  function(){
            (new \admin\controller\RoleMenu())->index();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/role_menu_save',
        'callback'  =>  function(){
            (new \admin\controller\RoleMenu())->save();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/user',
        'callback'  =>  function(){
            (new \admin\controller\User())->index();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/user_remove',
        'callback'  =>  function(){
            (new \admin\controller\User())->remove();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/user_edit',
        'callback'  =>  function(){
            (new \admin\controller\User())->edit();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/user_change_password',
        'callback'  =>  function(){
            (new \admin\controller\User())->change_password2();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/user_remove_role',
        'callback'  =>  function(){
            (new \admin\controller\User())->remove_role();
        },
        'isRegular' =>  false
    ],
    [
        'method'    =>  'post',
        'path'      =>  '/api/user_add_role',
        'callback'  =>  function(){
            (new \admin\controller\User())->add_role();
        },
        'isRegular' =>  false
    ]
];