<?php
namespace admin\controller;

class User extends Api
{
    public function change_password()
    {
        $old_password = $this->request->request('old_password','','trim');
        $new_password = $this->request->request('new_password','','trim');
        $repeat_password = $this->request->request('repeat_password','','trim');
        if (!$old_password){
            appJson(5004,'请输入旧密码');
        }
        if (!$new_password){
            appJson(5005,'请输入新密码');
        }
        if (!$repeat_password){
            appJson(5006,'请输入确认密码');
        }
        if (strcmp($new_password,$repeat_password) !== 0){
            appJson(5007,'两次密码不一致');
        }
        $user = $this->pdo->one("select pwd,pwd_time from `user` where id = :user_id",[':user_id'=>$this->user_id]);
        if (!$user){
            appJson(5008,'服务异常');
        }
        $password = getPassword($old_password,$user['pwd_time']);
        if (strcmp($password,$user['pwd']) !== 0){
            appJson(5009,'密码不正确');
        }
        //更新
        $now = time();
        $password = getPassword($new_password,$now);
        $res = $this->pdo->execute("update `user` set `pwd` = :pwd,`pwd_time` = :pwd_time where id = :id",[':pwd'=>$password,':pwd_time'=>$now,':id'=>$this->user_id]);
        if (!$res){
            appJson(5010,'操作失败');
        }
        appJson(0,'修改成功');
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $res = $this->pdo->execute("delete from `user_token` where user_id = :user_id",[':user_id'=>$this->user_id]);
        if (!$res){
            appJson(5004,'失败');
        } else {
            appJson(0,'登录成功');
        }
    }
}