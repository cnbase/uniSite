<?php
namespace admin\controller;

class Index extends Api
{
    //获取用户信息及菜单
    public function index()
    {
        //用户信息
        $user = $this->pdo->one("select nickname,avatar from `user` where id = :user_id",[':user_id'=>$this->user_id]);
        $return['user']['username'] = $user['nickname'];
        $return['user']['avatar'] = $user['avatar'];
        //菜单信息
        $return['menus'] = [];
        //1.所有角色ID
        $sql = "select r.`id` from `user_role` as ur join `role` as r on r.`id` = ur.`role_id` where r.`status` = 1 and ur.`user_id` = :user_id";
        $bind = [':user_id'=>$this->user_id];
        $role = $this->pdo->query($sql,$bind);
        if (!$role){
            appJson(0,'成功',$return);
        }
        $role_ids = array_column($role,'id');
        $role_ids = implode(',',$role_ids);
        //2.角色所关联的所有菜单
        $sql = "select m.`id`,m.`title`,m.`url`,m.`icon`,m.`pid` from `menu` as m join `role_menu` as rm on rm.`menu_id` = m.`id` where m.`status` = 1 and m.`module_id` = :module_id and rm.`role_id` in ({$role_ids}) order by m.`sort_no` asc";
        $bind = [':module_id'=>$this->module_id];
        $menus = $this->pdo->query($sql,$bind);
        if (!$menus){
            appJson(0,'成功',$return);
        }
        $menus = buildMenus($menus,0,'children');
        $return['menus'] = $menus;
        appJson(0,'成功',$return);
    }
}