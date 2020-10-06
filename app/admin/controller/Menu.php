<?php
namespace admin\controller;

class Menu extends Api
{
    //获取菜单页面数据
    public function index()
    {
        $page = $this->request->request('page',1,'intval');
        $size = $this->request->request('size',10,'intval');
        $limit = ($page - 1)*$size;
        $return['total'] = 0;
        $return['page'] = $page;
        $return['size'] = $size;
        $return['menus'] = [];
        //获取所有pid = 0的菜单
        $all = $this->pdo->one("select count(`id`) as `total` from `menu` where `status` >= 0 and `pid` = 0 and `module_id` = :module_id",[':module_id'=>$this->module_id]);
        if (!$all || $all['total'] == 0){
            appJson(0,'成功',$return);
        }
        $return['total'] = (int)$all['total'];

        $parent = $this->pdo->query("select `id` from `menu` where `status` >= 0 and `pid` = 0 and `module_id` = :module_id order by `id` desc limit {$limit},{$size}",[':module_id'=>$this->module_id]);
        $parent_ids = $parent?array_column($parent,'id'):[];
        $all_ids = $parent_ids;
        $children = $this->pdo->query("select `id` from `menu` where `status` >= 0 and `module_id` = :module_id and `pid` in (".implode(',',$parent_ids).")",[':module_id'=>$this->module_id]);
        $children_ids = $children?array_column($children,'id'):[];
        if ($children_ids){
            $all_ids = array_merge($all_ids,$children_ids);
            $grandson = $this->pdo->query("select `id` from `menu` where `status` >= 0 and `module_id` = :module_id and `pid` in (".implode(',',$children_ids).")",[':module_id'=>$this->module_id]);
            $grandson_ids = $grandson?array_column($grandson,'id'):[];
            if ($grandson_ids){
                $all_ids = array_merge($all_ids,$grandson_ids);
            }
        }
        $menus = $this->pdo->query("select *,CASE `status` WHEN 0 THEN '停用' WHEN 1 THEN '正常' END AS `status_text` from `menu` where `status` >= 0 and `module_id` = :module_id and id in (".implode(',',$all_ids).")",[':module_id'=>$this->module_id]);
        if ($menus){
            foreach ($menus as &$menu){
                $menu['pre_ids'] = $menu['pre_ids']?explode(',',$menu['pre_ids']):[];
            }
            $menus = buildMenus($menus,0,'children',false);
        }
        $return['menus'] = $menus;
        appJson(0,'成功',$return);
    }

    //新增或编辑菜单
    public function edit_menu()
    {
        $id = $this->request->request('id',0,'intval');
        $title = $this->request->request('title','','trim');
        $url = $this->request->request('url','','trim');
        $icon = $this->request->request('icon','','trim');
        $pre_ids = $this->request->request('pre_ids','','trim');
        $sort_no = $this->request->request('sort_no',0,'intval');
        $status = $this->request->request('status',0,'intval');
        $status = $status?1:0;
        if (!$title){
            appJson(5001,'请输入菜单名称');
        }
        $pid = $pre_ids?explode(',',$pre_ids):[];
        $pid = $pid?end($pid):0;
        if ($id){
            //编辑
            $menu = $this->pdo->one("select `id`,`pid`,`pre_ids` from menu where `module_id` = :module_id and `id` = :id",[':module_id'=>$this->module_id,':id'=>$id]);
            if (!$menu){
                appJson(5002,'修改的菜单不存在');
            }
            $this->pdo->beginTransaction();
            if ($menu['pid'] != $pid){
                //改变pid，判断合法性
                if ($pid){
                    $child = $this->pdo->one("select `id` from `menu` where find_in_set(:id,pre_ids) and `id` = :pid",[':id'=>$id,':pid'=>$pid]);
                    if ($child){
                        appJson(5003,'不能选他的子菜单为父级');
                    }
                }
                //修改所有子菜单pre_ids
                if (!$menu['pre_ids']){
                    //原来顶级菜单，调整为子菜单，sql执行直接拼接
                    $update = [
                        ':id'           =>  $id,
                        ':old_pre_ids'  =>  $menu['pre_ids'],
                        ':new_pre_ids'  =>  $pre_ids,
                        ':module_id'    =>  $this->module_id,
                    ];
                    $res = $this->pdo->execute("update `menu` set `pre_ids` = concat_ws(',',:new_pre_ids,:old_pre_ids) where find_in_set(:id,`pre_ids`) and module_id = :module_id",$update);
                    if ($res === false){
                        $this->pdo->rollback();
                        appJson(5004,'操作失败'.$this->pdo->error());
                    }
                } else {
                    //原来子菜单，调整为顶级或另一个子菜单，sql执行替换
                    $update = [
                        ':id'           =>  $id,
                        ':old_pre_ids'  =>  $pre_ids?$menu['pre_ids']:$menu['pre_ids'].',',//子菜单替换为顶级菜单，
                        ':new_pre_ids'  =>  $pre_ids,
                        ':module_id'    =>  $this->module_id,
                    ];
                    $res = $this->pdo->execute("update `menu` set `pre_ids` = replace(`pre_ids`,:old_pre_ids,:new_pre_ids) where find_in_set(:id,`pre_ids`) and module_id = :module_id",$update);
                    if ($res === false){
                        $this->pdo->rollback();
                        appJson(5005,'操作失败'.$this->pdo->error());
                    }
                }
            }
            $update = [
                ':id'       =>  $id,
                ':title'    =>  $title,
                ':url'      =>  $url,
                ':icon'     =>  $icon,
                ':pid'      =>  $pid,
                ':pre_ids'  =>  $pre_ids,
                ':sort_no'  =>  $sort_no,
                ':status'   =>  $status,
            ];
            $res = $this->pdo->execute("update `menu` set `title` = :title,`url` = :url,`icon` = :icon,`pid` = :pid,`pre_ids` = :pre_ids,`sort_no` = :sort_no,`status` = :status where id = :id",$update);
            if ($res === false){
                $this->pdo->rollback();
                appJson(5006,'操作失败'.$this->pdo->error());
            }
            $this->pdo->commit();
            appJson(0,'操作成功');
        } else {
            //新增
            $insert = [
                ':title'    =>  $title,
                ':url'      =>  $url,
                ':icon'     =>  $icon,
                ':pid'      =>  $pid,
                ':pre_ids'  =>  $pre_ids,
                ':sort_no'  =>  $sort_no,
                ':status'   =>  $status,
                ':module_id'    =>  1,
            ];
            $res = $this->pdo->execute("insert into `menu` (`title`,`url`,`icon`,`pid`,`pre_ids`,`sort_no`,`status`,`module_id`) values (:title,:url,:icon,:pid,:pre_ids,:sort_no,:status,:module_id)",$insert);
            if (!$res){
                appJson(5002,'操作失败'.$this->pdo->error());
            }
            appJson(0,'操作成功');
        }
    }

    //删除菜单及子菜单
    public function remove_menu()
    {
        $id = $this->request->request('id',0,'intval');
        if (!$id){
            appJson(5001,'[id]参数错误');
        }
        $menu = $this->pdo->one("select `id` from menu where `id` = :id",[':id'=>$id]);
        if (!$menu){
            appJson(5002,'菜单不存在');
        }
        //查找所有二级菜单
        $children = $this->pdo->query("select `id` from `menu` where `module_id` = :module_id and `pid` = :parent_id",[':module_id'=>$this->module_id,':parent_id'=>$id]);
        $children_ids = $children?array_column($children,'id'):[];
        if ($children_ids){
            $children_ids = implode(',',$children_ids);
            //删除
            $res = $this->pdo->execute("delete from `menu` where `module_id` = :module_id and (id = :id or pid = :parent_id or pid in (".$children_ids."))",[':module_id'=>$this->module_id,':id'=>$id,':parent_id'=>$id]);
        } else {
            //删除
            $res = $this->pdo->execute("delete from `menu` where `module_id` = :module_id and (id = :id or pid = :parent_id)",[':module_id'=>$this->module_id,':id'=>$id,':parent_id'=>$id]);
        }
        if (!$res){
            appJson(5003,'删除失败'.$this->pdo->error());
        } else {
            appJson(0,'成功');
        }
    }
}