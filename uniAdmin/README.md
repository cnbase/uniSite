# uniAdmin
vue+elementUI 搭建的后台模板

### 功能介绍

**效果图**

![首页预览](http://github.com/hiQbit/uniAdmin/raw/master/preview.png)

![首页预览](http://github.com/hiQbit/uniAdmin/raw/master/preview1.png)

* 支持菜单收缩
* 支持标签页右键操作，如关闭当前、关闭其他标签、刷新等

### 说明

**1. 安装说明**

* 下载文件，git clone https://github.com/hiQbit/uniAdmin.git
* 执行 `npm install`
* 本地测试 `npm run serve`
* 构建页面 `npm run build`

**2. 配置 `按需/全量` 引入 `elementUI` 组件**

    查看 `babel.config.js` 文件中 `babelMode` 配置
    `babelMode = true;//全量引入`
    `babelMode = false;//按需引入`

**3. 引入组件方式不同，语法不同**

参考 `src/pages` 目录下相关 `.js` 文件

* 按需引入组件：

```
import {
    Container
} from 'element-ui';
Vue.use(Container);
```

* 全量引入组件：

```
import ElementUI from 'element-ui'
Vue.use(ElementUI);
```

**4. 打包构建方式的选择**

请参考 `vue.config.js` 文件

注意：每次打包都会删除原构建目录所有文件重新打包，通过配置 `outputDir` 修改构建目录避免

```
buildMode=true;//全页面构建，打包全部文件
buildMode=false;//单页面构建，单独打包
```

**5. 页面配置文件 `src/pages.js`**

该配置项同 `vue-cli` ，https://cli.vuejs.org/zh/config/#pages

**6. 接口返回格式说明**

```
{
  "code": 0,//0表示成功，非0表示失败
  "data": {},
  "msg": "说明"
}
```

**7. 模板配置文件 `src/config.js`**

```
baseUrl:'', #网站域名
apiUrl:'', #接口域名
devMode:true, #开发模式,影响 getApi 方法
getApi：
开发模式，返回模拟接口数据，/api/*.json 文件
正式模式，请求实际 api 接口
```

**8. 目录文件说明**

* `src/utils/` 目录，封装公共函数

```
check_login.js #发起请求，判断是否登录
common.js #生成随机字符等公共函数
iAjax.js #封装axios的post和get请求
login.js #发起请求，登录
token.js #本地sessionStroage缓存处理函数
```

* `public/api/` 目录为模拟接口返回数据文件，上线可删除

```
change_password.json #修改登录密码接口数据
check_login.json #判断是否登录接口数据
index.json #模板所需数据，包括登录用户信息、菜单
login.json #登录接口数据
logout.json #退出接口数据
```

* `src/elementui/business_blue/` 目录为自定义 `elementUI` 主题样式

    不需要，可在相关 `pages.js` 文件，删除引用 `import '@/elementui/business_blue/theme/index.css'`
    
### 常见问题

**1. 使用模拟数据，本地测试，请求接口报错**

```
POST http://localhost:8080/api/login.json 404 (Not Found)
Uncaught (in promise) TypeError: Cannot set property 'type' of undefined
```

原因：绝大多数web服务器，都不允许静态文件响应POST请求

解决方式：
```
#修改 src/utils/iAjax.js
#改为开发模式，原理将post请求转为模拟get请求json文件
devMode = true;
```