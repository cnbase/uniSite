const devMode = false;
//域名
const baseUrl = '';
const apiUrl = '/api';

//拼接页面地址，可以对页面URL做特殊处理
const getPageUrl = function (pageName){
    return baseUrl+pageName;
}

//获取API地址
const getApi = function (api){
    if (devMode){
        return apiUrl+'/api'+api+'.json';
    } else {
        return apiUrl+api;
    }
}

export default {
    devMode,
    baseUrl,
    getApi,
    getPageUrl
}