<?php
namespace admin\controller;

class RoleMenu extends Api
{
    //获取菜单分配页面数据
    public function index()
    {
        $role_id = $this->request->request('role_id',0,'intval');
        $page = $this->request->request('page',1,'intval');
        $size = $this->request->request('size',10,'intval');
        $limit = ($page - 1)*$size;
        if (!$role_id){
            appJson(5001,'角色ID参数错误');
        }
        $sql = "select `id`,`name` from `role` where `id` = :id";
        $bind = [':id'=>$role_id];
        $role = $this->pdo->one($sql,$bind);
        if (!$role){
            appJson(5002,'角色不存在');
        }
        $return['role'] = $role;
        $return['total'] = 0;
        $return['page'] = $page;
        $return['size'] = $size;
        $return['menus'] = [];
        $return['allSelectedIds'] = [];
        $return['selectedAllStatus'] = false;
        //获取所有pid = 0的菜单
        $sql = "select count(`id`) as `total` from `menu` where `status` >= 0 and `pid` = 0 and `module_id` = :module_id";
        $bind = [':module_id'=>$this->module_id];
        $all = $this->pdo->one($sql,$bind);
        if (!$all || $all['total'] == 0){
            appJson(0,'成功',$return);
        }
        $return['total'] = (int)$all['total'];

        $sql = "select `id` from `menu` where `status` >= 0 and `pid` = 0 and `module_id` = :module_id order by `id` desc limit {$limit},{$size}";
        $parent = $this->pdo->query($sql,$bind);
        $parent_ids = $parent?array_column($parent,'id'):[];
        $all_ids = $parent_ids;
        $ids_str = implode(',',$parent_ids);
        $sql = "select `id` from `menu` where `status` >= 0 and `module_id` = :module_id and `pid` in ({$ids_str})";
        $children = $this->pdo->query($sql,$bind);
        $children_ids = $children?array_column($children,'id'):[];
        if ($children_ids){
            $all_ids = array_merge($all_ids,$children_ids);
            $ids_str = implode(',',$children_ids);
            $sql = "select `id` from `menu` where `status` >= 0 and `module_id` = :module_id and `pid` in ({$ids_str})";
            $grandson = $this->pdo->query($sql,$bind);
            $grandson_ids = $grandson?array_column($grandson,'id'):[];
            if ($grandson_ids){
                $all_ids = array_merge($all_ids,$grandson_ids);
            }
        }
        if (!$all_ids){
            appJson(5003,'数据异常');
        }
        $ids_str = implode(',',$all_ids);
        $sql = "select *,CASE `status` WHEN 0 THEN '停用' WHEN 1 THEN '正常' END AS `status_text` from `menu` where `status` >= 0 and `module_id` = :module_id and id in ({$ids_str})";
        $menus = $this->pdo->query($sql,$bind);
        if (!$menus){
            appJson(5004,'数据异常');
        }
        //查找所有菜单ID
        $sql = "select a.`id` from `menu` as a join `role_menu` as rm on rm.`menu_id` = a.`id` where a.`status` >= 0 and a.`module_id` = :module_id and rm.`role_id` = :role_id";
        $bind = [':module_id'=>$this->module_id,':role_id'=>$role_id];
        $allPageSelected = $this->pdo->query($sql,$bind);
        $return['allSelectedIds'] = $allPageSelected?array_column($allPageSelected,'id'):[];
        $intersect = array_intersect($return['allSelectedIds'],$all_ids);
        if (count($intersect) === count($all_ids)){
            $return['selectedAllStatus'] = true;
        }
        if ($menus){
            foreach ($menus as &$menu){
                $menu['pre_ids'] = $menu['pre_ids']?explode(',',$menu['pre_ids']):[];
                if (in_array($menu['id'],$return['allSelectedIds'])){
                    $menu['checked'] = true;
                } else {
                    $menu['checked'] = false;
                }
            }
            $menus = buildMenus($menus,0,'children',false);
        }
        $return['menus'] = $menus;
        appJson(0,'成功',$return);
    }

    //保存
    public function save()
    {
        $role_id = $this->request->request('role_id',0,'intval');
        $selected = $this->request->request('selected','','trim');
        if (!$role_id){
            appJson(5001,'角色ID参数错误');
        }
        $sql = "select `id`,`name` from `role` where `id` = :id";
        $bind = [':id'=>$role_id];
        $role = $this->pdo->one($sql,$bind);
        if (!$role){
            appJson(5002,'角色不存在');
        }
        $selectedArr = explode(',',$selected);
        $selected = implode(',',$selectedArr);//转换一下，防止异常格式数据
        $bind = [':role_id'=>$role_id];
        if (!$selected){
            //直接清空
            $sql = "delete from `role_menu` where `role_id` = :role_id";
            $res = $this->pdo->execute($sql,$bind);
            if (!$res){
                appJson(5003,'操作失败');
            }
        } else {
            //更新
            $this->pdo->beginTransaction();
            //1.查找应该删除的id
            $sql = "select `menu_id` from `role_menu` where `role_id` = :role_id and `menu_id` not in ({$selected})";
            $deleted = $this->pdo->query($sql,$bind);
            if ($deleted){
                //直接删除
                $deleted_ids = array_column($deleted,'menu_id');
                $deleted_ids = implode(',',$deleted_ids);
                $sql = "delete from `role_menu` where `role_id` = :role_id and `menu_id` in ({$deleted_ids})";
                $res = $this->pdo->execute($sql,$bind);
                if (!$res){
                    $this->pdo->rollback();
                    appJson(5004,'操作失败'.$this->pdo->error());
                }
            }
            //2.查找重复存在的id
            $sql = "select `menu_id` from `role_menu` where `role_id` = :role_id and `menu_id` in ({$selected})";
            $existed = $this->pdo->query($sql,$bind);
            if ($existed){
                //排除已存在ID
                $existed_ids = array_column($existed,'menu_id');
                $selectedArr = array_diff($selectedArr,$existed_ids);
            }
            //3.新增绑定
            if (!$selectedArr){
                $this->pdo->commit();
                appJson(0,'成功');
            }
            $insert = [
                ':role_id'  =>  $role_id,
            ];
            $sql = "insert into `role_menu` (`role_id`,`menu_id`) values ";
            foreach ($selectedArr as $k => $menu_id){
                $insert[':menu_id'.$k] = $menu_id;
                $sql .= " (:role_id,:menu_id{$k}),";
            }
            $sql = rtrim($sql,',');
            $res = $this->pdo->execute($sql,$insert);
            if (!$res){
                $this->pdo->rollback();
                appJson(5005,'操作失败'.$this->pdo->error());
            }
            $this->pdo->commit();
            appJson(0,'成功');
        }
    }
}