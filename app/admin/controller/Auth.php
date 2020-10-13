<?php
namespace admin\controller;

class Auth extends Api
{
    //获取权限页面数据
    public function index()
    {
        $page = $this->request->request('page',1,'intval');
        $size = $this->request->request('size',10,'intval');
        $limit = ($page - 1)*$size;
        $return['total'] = 0;
        $return['page'] = $page;
        $return['size'] = $size;
        $return['auths'] = [];
        //获取所有pid = 0的权限
        $all = $this->pdo->one("select count(`id`) as `total` from `auth` where `status` >= 0 and `pid` = 0 and `module_id` = :module_id",[':module_id'=>$this->module_id]);
        if (!$all || $all['total'] == 0){
            appJson(0,'成功',$return);
        }
        $return['total'] = (int)$all['total'];

        $parent = $this->pdo->query("select `id` from `auth` where `status` >= 0 and `pid` = 0 and `module_id` = :module_id order by `id` desc limit {$limit},{$size}",[':module_id'=>$this->module_id]);
        $parent_ids = $parent?array_column($parent,'id'):[];
        $all_ids = $parent_ids;
        $children = $this->pdo->query("select `id` from `auth` where `status` >= 0 and `module_id` = :module_id and `pid` in (".implode(',',$parent_ids).")",[':module_id'=>$this->module_id]);
        $children_ids = $children?array_column($children,'id'):[];
        if ($children_ids){
            $all_ids = array_merge($all_ids,$children_ids);
            $grandson = $this->pdo->query("select `id` from `auth` where `status` >= 0 and `module_id` = :module_id and `pid` in (".implode(',',$children_ids).")",[':module_id'=>$this->module_id]);
            $grandson_ids = $grandson?array_column($grandson,'id'):[];
            if ($grandson_ids){
                $all_ids = array_merge($all_ids,$grandson_ids);
            }
        }
        $auths = $this->pdo->query("select *,CASE `status` WHEN 0 THEN '停用' WHEN 1 THEN '正常' END AS `status_text` from `auth` where `status` >= 0 and `module_id` = :module_id and id in (".implode(',',$all_ids).")",[':module_id'=>$this->module_id]);
        if ($auths){
            foreach ($auths as &$auth){
                $auth['pre_ids'] = $auth['pre_ids']?explode(',',$auth['pre_ids']):[];
            }
            $auths = buildMenus($auths,0,'children',false);
        }
        $return['auths'] = $auths;
        appJson(0,'成功',$return);
    }

    //新增或编辑权限
    public function edit_auth()
    {
        $id = $this->request->request('id',0,'intval');
        $title = $this->request->request('title','','trim');
        $url = $this->request->request('url','','trim');
        $pre_ids = $this->request->request('pre_ids','','trim');
        $sort_no = $this->request->request('sort_no',0,'intval');
        $status = $this->request->request('status',0,'intval');
        $status = $status?1:0;
        if (!$title){
            appJson(5001,'请输入权限名称');
        }
        $pid = $pre_ids?explode(',',$pre_ids):[];
        $pid = $pid?end($pid):0;
        if ($id){
            if ($id == $pid){
                appJson(5007,'所选父级错误');
            }
            //编辑
            $auth = $this->pdo->one("select `id`,`pid`,`pre_ids` from auth where `module_id` = :module_id and `id` = :id",[':module_id'=>$this->module_id,':id'=>$id]);
            if (!$auth){
                appJson(5002,'修改的权限不存在');
            }
            $this->pdo->beginTransaction();
            if ($auth['pid'] != $pid){
                //改变pid，判断合法性
                if ($pid){
                    $child = $this->pdo->one("select `id` from `auth` where find_in_set(:id,pre_ids) and `id` = :pid",[':id'=>$id,':pid'=>$pid]);
                    if ($child){
                        appJson(5003,'不能选他的子权限为父级');
                    }
                }
                //修改所有子权限pre_ids
                if (!$auth['pre_ids']){
                    //原来顶级权限，调整为子权限，sql执行直接拼接
                    $update = [
                        ':id'           =>  $id,
                        ':old_pre_ids'  =>  $auth['pre_ids'],
                        ':new_pre_ids'  =>  $pre_ids,
                        ':module_id'    =>  $this->module_id,
                    ];
                    $res = $this->pdo->execute("update `auth` set `pre_ids` = concat_ws(',',:new_pre_ids,:old_pre_ids) where find_in_set(:id,`pre_ids`) and module_id = :module_id",$update);
                    if ($res === false){
                        $this->pdo->rollback();
                        appJson(5004,'操作失败'.$this->pdo->error());
                    }
                } else {
                    //原来子权限，调整为顶级或另一个子权限，sql执行替换
                    $update = [
                        ':id'           =>  $id,
                        ':old_pre_ids'  =>  $pre_ids?$auth['pre_ids']:$auth['pre_ids'].',',//子权限替换为顶级权限，
                        ':new_pre_ids'  =>  $pre_ids,
                        ':module_id'    =>  $this->module_id,
                    ];
                    $res = $this->pdo->execute("update `auth` set `pre_ids` = replace(`pre_ids`,:old_pre_ids,:new_pre_ids) where find_in_set(:id,`pre_ids`) and module_id = :module_id",$update);
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
                ':pid'      =>  $pid,
                ':pre_ids'  =>  $pre_ids,
                ':sort_no'  =>  $sort_no,
                ':status'   =>  $status,
            ];
            $res = $this->pdo->execute("update `auth` set `title` = :title,`url` = :url,`pid` = :pid,`pre_ids` = :pre_ids,`sort_no` = :sort_no,`status` = :status where id = :id",$update);
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
                ':pid'      =>  $pid,
                ':pre_ids'  =>  $pre_ids,
                ':sort_no'  =>  $sort_no,
                ':status'   =>  $status,
                ':module_id'    =>  1,
            ];
            $res = $this->pdo->execute("insert into `auth` (`title`,`url`,`pid`,`pre_ids`,`sort_no`,`status`,`module_id`) values (:title,:url,:pid,:pre_ids,:sort_no,:status,:module_id)",$insert);
            if (!$res){
                appJson(5002,'操作失败'.$this->pdo->error());
            }
            appJson(0,'操作成功');
        }
    }

    //删除权限及子权限
    public function remove_auth()
    {
        $id = $this->request->request('id',0,'intval');
        if (!$id){
            appJson(5001,'[id]参数错误');
        }
        $auth = $this->pdo->one("select `id` from auth where `id` = :id",[':id'=>$id]);
        if (!$auth){
            appJson(5002,'权限不存在');
        }
        //查找所有二级权限
        $children = $this->pdo->query("select `id` from `auth` where `module_id` = :module_id and `pid` = :parent_id",[':module_id'=>$this->module_id,':parent_id'=>$id]);
        $children_ids = $children?array_column($children,'id'):[];
        if ($children_ids){
            $children_ids = implode(',',$children_ids);
            //删除
            $res = $this->pdo->execute("delete from `auth` where `module_id` = :module_id and (id = :id or pid = :parent_id or pid in (".$children_ids."))",[':module_id'=>$this->module_id,':id'=>$id,':parent_id'=>$id]);
        } else {
            //删除
            $res = $this->pdo->execute("delete from `auth` where `module_id` = :module_id and (id = :id or pid = :parent_id)",[':module_id'=>$this->module_id,':id'=>$id,':parent_id'=>$id]);
        }
        if (!$res){
            appJson(5003,'删除失败'.$this->pdo->error());
        } else {
            appJson(0,'成功');
        }
    }
}