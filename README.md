# uniSite
采用 uniPHP+uniHTML 搭建的带后台管理的网站框架

- 100%前后端分离
- 后台管理暂无菜单及权限管理功能，需要数据库手动添加

### 安装说明

- **下载 `uniSite` 项目**

```
git clone https://github.com/hiQbit/uniSite.git
```

- 配置数据库

    - 导入数据库文件 `SQL_uni_site.sql`
    - 配置数据库参数 `/config/app.php`
    - 后台登录账号：admin/admin

- **安装 uniPHP 核心框架**

```
cd uniSite
# 需要安装php composer支持
composer update
```

- **基于 uniHTML 构建前后台**

    - 页面基于 `uniHTML v1.0` 版本构建
    - 仓库地址：https://github.com/hiQbit/uniHTML
    - 请详细阅读uniHTML相关文档，完成业务端页面的开发
  
    **安装注意事项：**
    
    ```
    git clone https://github.com/hiQbit/uniHTML
    cd uniHTML
    # 切换到 v1.0 或 更高版本
    git checkout -b v1.0.0
    # 需要安装 nodejs 环境
    npm install
    # 根据 uniHTML 安装说明完成配置
    # 如：config.js
    # 修改 outputDir:"../www/admin"
    # 修改 devMode=false,baseUrl和apiUrl="/api"
    # 修改 apiData={}
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
    'WEB_DIR'   =>  __DIR__.'/www/admin',//模块对外可访问根目录
    'APP_DIR'   =>  __DIR__.'/app',//应用目录，处理业务
    'CONF_DIR'  =>  __DIR__.'/config',//配置目录
    'ROUTE_DIR' =>  __DIR__.'/route',//路由目录
    'MODULE_NAME'   =>  'admin',//模块名称
])->run();
```

```
# index.php
require __DIR__.'/vendor/autoload.php';
//载入公共函数
require_once __DIR__.'/config/functions.php';
uniPHP::instance([
    'entryFile' =>  'index.php',//后台入口文件名
    'ROOT_DIR'  =>  __DIR__,//项目根目录
    'WEB_DIR'   =>  __DIR__.'/www/index',//模块对外可访问根目录
    'APP_DIR'   =>  __DIR__.'/app',//应用目录，处理业务
    'CONF_DIR'  =>  __DIR__.'/config',//配置目录
    'ROUTE_DIR' =>  __DIR__.'/route',//路由目录
    'MODULE_NAME'   =>  'index',//模块名称
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
后台模块根目录：/www/admin
入口文件：/admin.php
```