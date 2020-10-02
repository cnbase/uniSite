# uniSite
采用 uniPHP+uniAdmin 搭建的带后台管理的网站框架

- 100%前后端分离

### 安装说明

- **下载 `uniSite` 项目**

```
git clone https://github.com/hiQbit/uniSite.git
```

- **安装 uniPHP 核心框架**

```
cd uniSite
# 需要安装php composer支持
composer update
```

- **基于 uniAdmin 后台UI**

    - 后台页面基于uniAdmin v1.0.7版本构建
    - 仓库地址：https://github.com/hiQbit/uniAdmin
    - 请详细阅读uniAdmin相关文档，完成业务端页面的开发

    ```
    npm install
    #完成业务端页面开发后
    npm run build
    ```
    
    也可以删除uniAdmin目录，自行安装新版本

    **自行安装注意事项：**
    
    ```
    git clone https://github.com/hiQbit/uniAdmin
    cd uniAdmin
    # 切换到 v1.0 或 更高版本
    git checkout -b v1.0.7
    # 需要安装 nodejs 环境
    npm install
    # 根据 uniAdmin 安装说明完成配置
    # 如：
    # 修改 vue.config.js 的 outputDir:"../admin"
    # 修改 src/config.js 的 devMode=false,baseUrl和apiUrl="/api"
    # 删除 public/api/ 下面的所有模拟接口json文件
    npm run build
    # 完成页面构建
    ```

- **修改入口文件相关配置**

```
# admin.php
require __DIR__.'/vendor/autoload.php';
//载入公共函数
require_once __DIR__.'/config/functions.php';
uniPHP::instance([
    'entryFile' =>  'admin.php',//后台入口文件名
    'ROOT_DIR'  =>  __DIR__,//项目根目录
    'WEB_DIR'   =>  __DIR__.'/admin',//模块对外可访问根目录
    'APP_DIR'   =>  __DIR__.'/app',//应用目录，处理业务
    'CONF_DIR'  =>  __DIR__.'/config',//配置目录
    'ROUTE_DIR' =>  __DIR__.'/route',//路由目录
    'MODULE_NAME'   =>  'admin',//模块名称
])->run();
```

注意 `entryFile` 在二级目录时，需要加上访问路径：

如访问 `http://example.com/dir/admin.php` 地址时，则需要设置为 `dir/admin.php`

- **配置nginx**

```
参考 nginx.example.conf
```

原理：网站根目录和入口文件位置可以不同，当访问路径不存在时，nginx转发给php到入口文件处理

```
后台模块根目录：/admin
入口文件：/admin.php
```