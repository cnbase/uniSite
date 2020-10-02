import iAjax from "@/utils/iAjax";
import Token from '@/utils/token'
import Config from '@/config'

const login = function (data){
    return new Promise(function (resolve, reject) {
        let tokenPrefix = Token.getTokenPrefix();
        if (!tokenPrefix){
            resolve({code:5001,data:{},msg:'5001-请刷新页面重试'});
        }
        //执行登录
        let api = Config.getApi('/login');
        iAjax.post(api,data).then(function (res){
            if (res.code === 0){
                //写入缓存
                Token.writeToken(res.data.token);
            }
            resolve(res);
        }).catch(function (error){
            reject(error);
        })
    })
}

export default {
    login
}