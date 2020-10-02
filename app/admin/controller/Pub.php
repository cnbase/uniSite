<?php
namespace admin\controller;

class Pub extends ApiBase
{
    /**
     * 判断是否登录
     */
    public function check_login()
    {
        $token = $this->request->request('token','','trim');
        if (!$token){
            appJson(5001,'[token]参数错误');
        }
        $user_token = $this->pdo->one('select user_id from `user_token` where token = :token',[':token'=>$token]);
        if (!$user_token){
            appJson(5002,'未登录');
        }
        appJson(0,'校验通过');
    }

    /**
     * 登录成功
     */
    public function login()
    {
        $username = $this->request->request('username','','trim');
        $password = $this->request->request('password','','trim');
        if (!$username){
            appJson(5001,'请输入用户名');
        }
        if (!$password){
            appJson(5002,'请输入密码');
        }
        $user = $this->pdo->one("select id,pwd,pwd_time from `user` where username = :username",[':username'=>$username]);
        if (!$user){
            appJson(5003,'用户不存在或密码错误');
        }
        $getPassword = getPassword($password,$user['pwd_time']);
        if (strcmp($user['pwd'],$getPassword) !== 0){
            appJson(5004,'密码错误');
        }
        //生成token
        $now = time();
        $token = getPassword($user['id'],$now);
        $user_token = $this->pdo->one("select id from `user_token` where user_id = :user_id",[':user_id'=>$user['id']]);
        if ($user_token){
            //刷新
            $res = $this->pdo->execute("update `user_token` set token = :token,token_time = :token_time where id = :id",[':token'=>$token,':token_time'=>$now,':id'=>$user_token['id']]);
            if (!$res){
                appJson(5005,'登录失败');
            }
        } else {
            //新增
            $res = $this->pdo->execute("insert into `user_token` (`user_id`,`token`,`token_time`) value (:user_id,:token,:token_time)",[':user_id'=>$user['id'],':token'=>$token,':token_time'=>$now]);
            if (!$res){
                appJson(5006,'登录失败');
            }
        }
        appJson(0,'登录成功',['token'=>$token]);
    }
}