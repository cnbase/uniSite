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

- **安装 uniAdmin 后台UI**

```
git clone https://github.com/hiQbit/uniAdmin.git
cd uniAdmin
# 切换到 v1.0 版本
git checkout v1.0.0
# 需要安装 nodejs 环境
npm install
# 根据 uniAdmin 安装说明完成配置
```

- **修改入口文件相关配置**

```
(new uniPHP([
    'entryFile' =>  'index.php',//入口文件名
    'WEB_DIR'   =>  __DIR__.'/www',//网站可访问目录
    'APP_DIR'   =>  __DIR__.'/app',//应用目录，处理业务
    'CONF_DIR'  =>  __DIR__.'/config',//配置目录
    'ROUTE_DIR' =>  __DIR__.'/route',//路由目录
    'MODULE_NAME'   =>  'index',//模块名称
]))->run();
```

注意 `entryFile` 的定义：

需要加上访问路径：如访问像 `http://example.com/dir/index.php` 地址时，则需要设置为 `dir/index.php`

- **配置nginx**

```
参考 nginx.example.conf
```

注意：网站根目录和入口文件位置，当访问路径不存在时，nginx转发给php到入口文件处理

```
网站根目录：/www
入口文件：/index.php
```