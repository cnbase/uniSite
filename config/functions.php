<?php
/**
 * 返回API统一格式
 * @param int $code
 * @param string $msg
 * @param array $data
 */
function appJson(int $code = 0,string $msg = '',array $data = []){
    header('Content-type:application/json;charset=utf-8');
    echo json_encode(['code'=>$code,'msg'=>$msg,'data'=>$data], JSON_UNESCAPED_UNICODE);
    exit();
}

// 获取当前服务器配置采用https/http
function get_protocol(){
    return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';
}

// 获取当前URL
function get_current_url(){
    return get_protocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

//生成邀请码
function createCode($num)
{
    static $source_string = 'E5FCDGUV67MWX89KLYZ3HQA4B1NPIJ2RST'; //1~9A~Z字符打乱,排除0和O
    $code = '';
    while ($num > 0) {
        $mod = bcmod($num, 34);
        $num = bcdiv(bcsub($num,(int)$mod),34);
        $code = $source_string[(int)$mod].$code;
    }
    if(empty($code[3])){
        $code = str_pad($code, 4, '0', STR_PAD_LEFT);
    }
    return $code;
}

//解析邀请码
function deCode($code)
{
    static $source_string = 'E5FCDGUV67MWX89KLYZ3HQA4B1NPIJ2RST';//1~9A~Z字符打乱,排除0和O
    if (strrpos($code, '0') !== false){
        $code = substr($code, strrpos($code, '0')+1);
    }
    $len = strlen($code);
    $code = strrev($code);
    $num = 0;
    for ($i=0; $i < $len; $i++){
        $num += strpos($source_string, $code[$i]) * bcpow(34, $i);
    }
    return $num;
}

/**
 * 递归组装菜单
 * @param array $all_menus
 * @param int $pid
 * @param string $childName 子节点key名称
 * @param bool $childInit 当子节点无时，是否增加child键
 * @return array
 */
function buildMenus(array $all_menus,int $pid = 0,string $childName = 'child',bool $childInit = true)
{
    $parent = [];
    foreach ($all_menus as $key => $menu){
        if ($menu['pid'] == $pid){
            $parent[] = $menu;
            unset($all_menus[$key]);
        }
    }
    if ($parent){
        foreach ($parent as $k => $p){
            $child = buildMenus($all_menus,$p['id'],$childName,$childInit);
            if ($childInit){
                $parent[$k][$childName] = $child;
            } else {
                if ($child) $parent[$k][$childName] = $child;
            }
        }
    }
    return $parent;
}

/**
 * 加密
 * @param string $password
 * @param string $salt
 * @return string
 */
function getPassword(string $password, string $salt = '')
{
    return sha1($password.$salt);
}