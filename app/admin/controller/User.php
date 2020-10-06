<?php
namespace admin\controller;

class User extends Api
{
    //框架页面，修改当前登录用户密码
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

    //用户管理菜单
    public function index()
    {
        $page = $this->request->request('page',1,'intval');
        $size = $this->request->request('size',10,'intval');
        $limit = ($page - 1)*$size;
        $return['total'] = 0;
        $return['page'] = $page;
        $return['size'] = $size;
        $return['users'] = [];
        $return['roles'] = [];
        //获取角色列表
        $sql = "select `id`,`name` from `role` where `status` >= 0";
        $roles = $this->pdo->query($sql);
        $return['roles'] = $roles?:[];
        //获取用户列表
        $sql = "select count(*) `total` from `user` where `status` >= 0";
        $all = $this->pdo->one($sql);
        if (!$all) {
            appJson(5001,'获取数据失败'.$this->pdo->error());
        }
        $return['total'] = (int)$all['total'];
        if (!$return['total']){
            appJson(0,'成功',$return);
        }
        $sql = "select `id`,`username`,`nickname`,`avatar`,`status`,CASE `status` WHEN 0 THEN '停用' WHEN 1 THEN '正常' END AS `status_text` from `user` where `status` >= 0 order by `id` desc limit {$limit},{$size}";
        $users = $this->pdo->query($sql);
        if ($users === false){
            appJson(5002,'失败'.$this->pdo->error());
        }
        $users = $users?:[];
        $sql = "select ur.`id`,ur.`user_id`,ur.`role_id`,r.`name` from `user_role` as ur join `role` as r on r.`id` = ur.`role_id` where r.`status` >= 0 and ur.`user_id` = :user_id";
        foreach ($users as &$user){
            //查找该用户绑定的角色
            $bind = [':user_id'=>$user['id']];
            $user_roles = $this->pdo->query($sql,$bind);
            $user['roles'] = $user_roles?:[];
        }
        $return['users'] = $users;
        appJson(0,'成功',$return);
    }

    //新增或编辑用户
    public function edit()
    {
        $id = $this->request->request('id',0,'intval');
        $username = $this->request->request('username','','trim');
        $nickname = $this->request->request('nickname','','trim');
        $avatar = $this->request->request('avatar','','trim');
        $status = $this->request->request('status',0,'intval');
        $status = $status?1:0;
        if (!$username){
            appJson(5001,'请输入登录用户名');
        }
        $sql = "select `id` from `user` where `username` = :username";
        $user = $this->pdo->one($sql,[':username'=>$username]);
        if ($id){
            //编辑
            if ($user && (int)$user['id'] !== (int)$id){
                appJson(5002,'该用户名已存在，请重新填写');
            }
            $update = [
                ':id'   =>  $id,
                ':username' =>  $username,
                ':nickname' =>  $nickname,
                ':avatar'   =>  $avatar,
                ':status'   =>  $status
            ];
            $sql = "update `user` set `username` = :username,`nickname` = :nickname,`avatar` = :avatar,`status` = :status where `id` = :id";
            $res = $this->pdo->execute($sql,$update);
            if ($res === false){
                appJson(5003,'操作失败');
            }
            appJson(0,'操作成功');
        } else {
            //新增
            if ($user){
                appJson(5004,'该用户名已存在');
            }
            $insert = [
                ':username' =>  $username,
                ':nickname' =>  $nickname,
                ':avatar'   =>  $avatar,
                ':status'   =>  $status
            ];
            $sql = "insert into `user` (`username`,`nickname`,`avatar`,`status`) values (:username,:nickname,:avatar,:status)";
            $res = $this->pdo->execute($sql,$insert);
            if (!$res){
                appJson(5005,'操作失败');
            }
            appJson(0,'添加成功，请初始化登录密码');
        }
    }

    //绑定角色
    public function add_role()
    {
        $user_ids = $this->request->request('user_ids','','trim');
        $role_ids = $this->request->request('role_ids','','trim');
        $user_ids_arr = explode(',',$user_ids);
        $role_ids_arr = explode(',',$role_ids);
        $user_ids = implode(',',$user_ids_arr);
        $role_ids = implode(',',$role_ids_arr);
        if (!$user_ids){
            appJson(5001,'请选择一位用户');
        }
        if (!$role_ids){
            appJson(5002,'请至少选择一个角色');
        }
        //判断合法性
        $sql = "select count(`id`) `total` from `user` where `status` >= 0 and `id` in ({$user_ids})";
        $users = $this->pdo->one($sql);
        if (!$users || (int)$users['total'] !== count($user_ids_arr)){
            appJson(5003,'提交数据，存在不可操作用户ID');
        }
        $sql = "select count(`id`) `total` from `role` where `status` >= 0 and `id` in ({$role_ids})";
        $roles = $this->pdo->one($sql);
        if (!$roles || (int)$roles['total'] !== count($role_ids_arr)){
            appJson(5004,'提交数据，存在不可操作角色ID');
        }
        //1.获取所有用户的所有角色ID
        $sql = "select * from `user_role` where `user_id` in ({$user_ids})";
        $user_roles = $this->pdo->query($sql);
        if ($user_roles === false){
            appJson(5005,'操作失败'.$this->pdo->error());
        }
        if (!$user_roles){
            //全部用户未绑定任何角色
            $sql = "insert into `user_role` (`user_id`,`role_id`) values ";
            $bind = [];
            foreach ($user_ids_arr as $x => $user_id){
                foreach ($role_ids_arr as $y => $role_id){
                    $bind[':user_id'.$x] = $user_id;
                    $bind[':role_id'.$y] = $role_id;
                    $sql .= "(:user_id{$x},:role_id{$y}),";
                }
            }
            $sql = rtrim($sql,',');
            $res = $this->pdo->execute($sql,$bind);
            if (!$res){
                appJson(5006,'操作失败'.$this->pdo->error());
            }
            appJson(0,'操作成功');
        } else {
            //存在部分绑定，则跳过，避免重复添加
            $tempArr = [];
            foreach ($user_roles as $user_role){
                $tempArr[$user_role['user_id']][] = $user_role['role_id'];
            }
            $sql = "insert into `user_role` (`user_id`,`role_id`) values ";
            $bind = [];
            foreach ($user_ids_arr as $x => $user_id){
                foreach ($role_ids_arr as $y => $role_id){
                    if (!isset($tempArr[$user_id]) || !in_array($role_id,$tempArr[$user_id])){
                        $bind[':user_id'.$x] = $user_id;
                        $bind[':role_id'.$y] = $role_id;
                        $sql .= "(:user_id{$x},:role_id{$y}),";
                    }
                }
            }
            if ($bind) {
                $sql = rtrim($sql,',');
                $res = $this->pdo->execute($sql,$bind);
                if (!$res){
                    appJson(5007,'操作失败'.$this->pdo->error());
                }
            }
            appJson(0,'操作成功');
        }
    }

    //移除角色
    public function remove_role()
    {
        $user_id = $this->request->request('user_id',0,'intval');
        $role_id = $this->request->request('role_id',0,'intval');
        if (!$user_id || !$role_id){
            appJson(5001,'参数错误');
        }
        $sql = "delete from `user_role` where `user_id` = :user_id and `role_id` = :role_id";
        $bind = [
            ':user_id'  =>  $user_id,
            ':role_id'  =>  $role_id,
        ];
        $res = $this->pdo->execute($sql,$bind);
        if (!$res){
            appJson(5002,'操作失败'.$this->pdo->error());
        }
        appJson(0,'操作成功');
    }

    //管理员修改所有用户登录密码
    public function change_password2()
    {
        $user_id = $this->request->request('user_id',0,'intval');
        $password = $this->request->request('password','','trim');
        if (!$user_id){
            appJson(5001,'参数错误');
        }
        if (!$password){
            appJson(5002,'请输入新的登录密码');
        }
        $bind = [':user_id'=>$user_id];
        $user = $this->pdo->one("select `id` from `user` where `status` >= 0 and `id` = :user_id",$bind);
        if (!$user){
            appJson(5003,'该用户不存在');
        }
        $now = time();
        $new_password = getPassword($password,$now);
        $bind[':pwd'] = $new_password;
        $bind[':pwd_time'] = $now;
        $sql = "update `user` set `pwd` = :pwd,`pwd_time` = :pwd_time where `id` = :user_id";
        $res = $this->pdo->execute($sql,$bind);
        if ($res === false){
            appJson(5004,'操作失败'.$this->pdo->error());
        }
        appJson(0,'操作成功');
    }

    //删除用户
    public function remove()
    {
        $user_id = $this->request->request('user_id',0,'intval');
        if (!$user_id){
            appJson(5001,'参数错误');
        }
        $sql = "select `id` from `user` where `status` >= 0 and `id` = :user_id";
        $bind = [':user_id'=>$user_id];
        $user = $this->pdo->one($sql,$bind);
        if (!$user){
            appJson(5001,'该用户不存在');
        }
        $this->pdo->beginTransaction();
        //1.删除该用户
        $res = $this->pdo->execute("delete from `user` where `id` = :user_id",$bind);
        if (!$res){
            $this->pdo->rollback();
            appJson(5002,'操作失败'.$this->pdo->error());
        }
        //2.删除该用户关联角色
        $res = $this->pdo->execute("delete from `user_role` where `user_id` = :user_id",$bind);
        if (!$res){
            $this->pdo->rollback();
            appJson(5002,'操作失败'.$this->pdo->error());
        }
        //3.删除用户登录token
        $res = $this->pdo->execute("delete from `user_token` where `user_id` = :user_id",$bind);
        if (!$res){
            $this->pdo->rollback();
            appJson(5002,'操作失败'.$this->pdo->error());
        }
        $this->pdo->commit();
        appJson(0,'操作成功');
    }
}