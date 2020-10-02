//随机字符串
const randomStr = function (len){
    len = len || 8
    let _char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    let pos = _char.length
    let str = '';
    for (let i = 0; i < len; i++){
        str += _char.charAt(Math.floor(Math.random()*pos));
    }
    return str;
}

export default {
    randomStr
}