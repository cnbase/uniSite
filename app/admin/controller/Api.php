<?php
namespace admin\controller;

use uniPHP\core\Router;

class Api extends ApiBase
{
    /**
     * @var int 登录用户ID
     */
    protected int $user_id;

    /**
     * Api constructor.
     * @throws \ErrorException
     */
    public function __construct()
    {
        parent::__construct();
        $token = $this->request->request('token','','trim');
        if (!$token){
            appJson(5001,'[token]参数错误');
        }
        $user_token = $this->pdo->one('select user_id from `user_token` where token = :token',[':token'=>$token]);
        if (!$user_token){
            appJson(5002,'拒绝访问');
        }
        $this->user_id = $user_token['user_id'];
        $this->checkAuth();
    }

    /**
     * 鉴权
     * @throws \ErrorException
     */
    protected function checkAuth()
    {
        /**
         * @var Router $Router
         */
        $Router = \uniPHP::use('Router');
        $auth_url = $Router->rule;
        $sql = "select a.`id` from `auth` as a join `role_auth` as ra on ra.`auth_id` = a.`id` join `user_role` as ur on ur.`role_id` = ra.`role_id` join `role` as r on r.`id` = ur.`role_id` where a.`status` = 1 and a.`module_id` = :module_id and r.`status` = 1 and a.`url` = :url and ur.`user_id` = :user_id";
        $bind = [
            ':user_id'=>$this->user_id,
            ':url'=>$auth_url,
            ':module_id'=>$this->module_id
        ];
        $auth = $this->pdo->one($sql,$bind);
        if (!$auth){
            appJson(5003,'无权访问');
        }
    }
}