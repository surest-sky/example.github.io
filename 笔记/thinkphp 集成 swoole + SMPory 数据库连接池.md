# thinkphp 集成 swoole + SMPory 数据库连接池

## 环境 

    deepin + 宝塔

## 安装swoole | 配置http服务器

![图片描述...](http://cdn.surest.cn/FpgAepjL2gK_7nnjXgd8nOGrzLpE)

安装 `think-swoole` 

文档: [https://www.kancloud.cn/thinkphp/think-swoole/722895](https://www.kancloud.cn/thinkphp/think-swoole/722895)

地址: 

    composer require topthink/think-swoole


### 启动

    php think swoole start

    # 出现地址后就可以访问了

### 映射到我们的域名下面

现在的地址是对内的端口,我们需要进行配置,转化为对外可以进行访问swoole服务的地址

配置 nginx / 域名.conf

    upstream swoole_server {
        # 你的swoole启动后的地址
        server 192.168.2.188:9501;
    }

    server
    {
        ....
        location / {
    	    proxy_pass http://swoole_server;
        }
    }

以上就是负载均衡到你的swoole地址去了, 然后再次打开你之前配置的域名地址, 现在请求的就是swoole服务了

## 安装 SMProxy

文档: [https://smproxy.louislivi.com/#/README](https://smproxy.louislivi.com/#/README)

    git clone https://github.com/louislivi/SMProxy.git

    cd SMProxy

    # 如果很慢的话先执行: 
    # composer config repo.packagist composer https://mirrors.aliyun.com/composer/
    composer intsall

### 配置 SMProxy

> 具体参数配置, 可见文档

如下配置 : server 配置的是 SMProxy 服务的信息, 例如thinkphp 需要连接 SMProxy 连接池 服务, 如下是配置连接服务的账号密码信息

     "server": {
        "user": "root",
        "password": "root",
        "charset": "utf8mb4",
        "host": "0.0.0.0",
        "port": "3366",
        ...

配置如上完成之后,修改 database.php

    ....
    // 服务器地址
    'hostname'        => '0.0.0.0',
    // 数据库名
    'database'        => 'd88_07_30',
    // 用户名
    'username'        => 'root',
    // 密码
    'password'        => 'root',
    // 端口
    'hostport'        => '3366',
    ....

配置 database.json

需要注意的地方, databases 下面的参数配置的key 值是你的数据库名称 例如我的数据库名称为 surest , 则配置如下

    ...
    "serverInfo": {
      "app": {
        "write": {
          "host": ["192.168.2.199"],
          "port": 3306,
          "timeout": 2,
          "account": "root"
        },
        "read": {
          "host": ["192.168.2.199"],
          "port": 3306,
          "timeout": 2,
          "account": "root"
        }
      }
    },
    "databases": {
      "surest": {
        "serverInfo": "app",
        "startConns": "swoole_cpu_num()*10",
        "maxSpareConns": "swoole_cpu_num()*10",
        "maxSpareExp": 3600,
        "maxConns": "swoole_cpu_num()*20",
        "charset": "utf8mb4"
     ....

### 配置环境变量 | 启动 

    # /SMProxy/bin 为你安装的 目录/命令 地址
    export PATH=$PATH:/SMProxy/bin

    SMProxy start

### 访问域名

### 性能对比

1000个用户 100个并发

搭载前

![图片描述...](http://cdn.surest.cn/FjaOn8-RdjFLXevgyciCx-fgr9Mm)

搭载后  - 

![图片描述...](http://cdn.surest.cn/FrpNoOMlFio5GsxZFEuKZfvmGcOE)