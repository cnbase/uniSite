import Common from './common'

const storage = window.sessionStorage

//缓存token
const writeToken = function (token) {
    storage.setItem(getTokenPrefix()+'_token',token);
}

//获取token
const getToken = function () {
    let tokenPrefix = getTokenPrefix()
    if (!tokenPrefix){
        return false;
    }
    return storage.getItem(tokenPrefix+'_token');
}

//删除token
const removeToken = function (func) {
    storage.removeItem(getTokenPrefix()+'_token');
    if (typeof func === 'function'){
        func();
    }
}

//缓存token前缀
const writeTokenPrefix = function (){
    let tokenPrefix = storage.getItem('tokenPrefix')
    if (!tokenPrefix){
        tokenPrefix = Common.randomStr(8);
        storage.setItem('tokenPrefix',tokenPrefix);
        //移除已存在token
        storage.removeItem(tokenPrefix+'_token');
    }
    return tokenPrefix;
}

//前缀不存在时，自动创建；存在则返回
const getTokenPrefix = function (){
    let tokenPrefix = storage.getItem('tokenPrefix')
    if (!tokenPrefix){
        return null;
    }
    return tokenPrefix;
}

export default {
    writeToken,
    getToken,
    removeToken,
    writeTokenPrefix,
    getTokenPrefix
}