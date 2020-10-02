<?php
namespace admin\controller;

class Index extends Api
{
    //获取用户信息及菜单
    public function index()
    {
        $user = $this->pdo->one("select nickname,avatar from `user` where id = :user_id",[':user_id'=>$this->user_id]);
        $return['user']['username'] = $user['nickname'];
        $return['user']['avatar'] = $user['avatar'];
        $menus = $this->pdo->query("select m.id,m.title,m.url,m.icon,m.pid from `user_menu` um join `menu` m on m.id = um.menu_id where um.user_id = :user_id and m.`status` = 1 order by m.sort_no asc",[':user_id'=>$this->user_id]);
        $menus = buildMenus($menus?:[],0,'children');
        $return['menus'] = $menus;
        appJson(0,'成功',$return);
    }
}