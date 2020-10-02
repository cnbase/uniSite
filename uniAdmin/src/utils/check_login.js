import iAjax from "@/utils/iAjax";
import Token from "@/utils/token"
import Config from "@/config"

const checkLogin = function (){
    return new Promise(function (resolve, reject) {
        let token = Token.getToken();
        if (!token){
            resolve({code:5001,data:{},msg:'5001-请先登录'});
        } else {
            //校验token
            let api = Config.getApi('/check_login');
            iAjax.post(api,{token:token}).then(function (res){
                resolve(res);
            }).catch(function (error){
                reject(error);
            })
        }
    })
}

export default {
    checkLogin
}