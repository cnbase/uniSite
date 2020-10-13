<?php
namespace admin\controller;

class Role extends Api
{
    //获取角色管理页面数据
    public function index()
    {
        $page = $this->request->request('page',1,'intval');
        $size = $this->request->request('size',10,'intval');
        $limit = ($page - 1)*$size;
        $return['total'] = 0;
        $return['page'] = $page;
        $return['size'] = $size;
        $return['roles'] = [];
        //获取角色列表
        $sql = "select count(`id`) as `total` from `role` where `status` >= 0";
        $all = $this->pdo->one($sql);
        if (!$all){
            appJson(0,'成功',$return);
        }
        $return['total'] = $all['total'];
        $sql = "select * from `role` where `status` >= 0 order by `id` desc limit {$limit},{$size}";
        $roles = $this->pdo->query($sql);
        if (!$roles){
            appJson(5001,'系统错误'.$this->pdo->error());
        }
        foreach ($roles as &$role){
            $role['status_text'] = $role['status']?'正常':'停用';
        }
        $return['roles'] = $roles;
        appJson(0,'成功',$return);
    }

    //新增/编辑角色
    public function edit_role()
    {
        $id = $this->request->request('id',0,'intval');
        $name = $this->request->request('name','','trim');
        $status = $this->request->request('status',0,'intval');
        $status = $status?1:0;
        if (!$name){
            appJson(5001,'请输入角色名称');
        }
        $role = $this->pdo->one("select `id` from `role` where `name` = :name",[':name'=>$name]);
        if ($id){
            //编辑
            if ($role && $role['id'] != $id){
                appJson(5002,'该角色名称已存在');
            }
            $update = [
                ':id'       =>  $id,
                ':name'     =>  $name,
                ':status'   =>  $status
            ];
            $res = $this->pdo->execute("update `role` set `name` = :name,`status` = :status where `id` = :id",$update);
            if ($res === false){
                appJson(5003,'操作失败'.$this->pdo->error());
            }
            appJson(0,'操作成功');
        } else {
            //新增
            if ($role){
                appJson(5002,'该角色名称已存在');
            }
            $insert = [
                ':name'     =>  $name,
                ':status'   =>  $status
            ];
            $res = $this->pdo->execute("insert into `role` (`name`,`status`) values (:name,:status)",$insert);
            if (!$res){
                appJson(5003,'操作失败'.$this->pdo->error());
            }
            appJson(0,'操作成功');
        }
    }

    //删除角色
    public function remove_role()
    {
        $id = $this->request->request('id',0,'intval');
        if (!$id){
            appJson(5001,'[id]参数错误');
        }
        $this->pdo->beginTransaction();
        $bind = [':id'=>$id];
        $res = $this->pdo->execute("delete from `role` where `id` = :id",$bind);
        if (!$res){
            $this->pdo->rollback();
            appJson(5002,'删除失败');
        }
        //删除角色所属权限及菜单
        $res = $this->pdo->execute("delete from `role_auth` where `role_id` = :id",$bind);
        if ($res === false){
            $this->pdo->rollback();
            appJson(5003,'删除关联权限失败');
        }
        $res = $this->pdo->execute("delete from `role_menu` where `role_id` = :id",$bind);
        if ($res === false){
            $this->pdo->rollback();
            appJson(5004,'删除关联菜单失败');
        }
        //删除关联的用户
        $res = $this->pdo->execute("delete from `user_role` where `role_id` = :id",$bind);
        if ($res === false){
            $this->pdo->rollback();
            appJson(5002,'删除关联用户失败');
        }
        $this->pdo->commit();
        appJson(0,'删除成功');
    }
}