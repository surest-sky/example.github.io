
### 安装 1） 不建议

1) https://www.runoob.com/docker/windows-docker-install.html


打开 Docker Quickstart Terminal 会出现去github下载

去这里下载这个源：

https://pan.baidu.com/s/1GTeroFpwgmLDwAQU1pJ4Bg

 C:\Program Files\Docker Toolbox文件夹下的boot2docker.iso 复制到C:\Users\lzy\.docker\machine\cache下，然后断开网络，就可以继续安装完毕了，亲测可行！

再打开 Docker Quickstart Terminal

进行到一半的时候打开网络，就安装好了

## 安装2） 推荐

下载： 注册一个账号密码

https://www.docker.com/products/docker-desktop

安装后，配置镜像源如下


## 切换镜像源

### 如何申请镜像源

https://cr.console.aliyun.com/cn-hangzhou/instances/mirrors

列表里面有个镜像加速器

![图片描述...](http://cdn.surest.cn/FiI9OXZfwlQajkrYf64tAdfHkiF_)


--registry-mirror https://5zpts8zx.mirror.aliyuncs.com

我自己的是：

    "registry-mirrors": ["https://5zpts8zx.mirror.aliyuncs.com"]

## 运行一个docker

> 不配置镜像加速会很卡

docker run ubuntu:15.10 /bin/echo "Hello world"

### 交互式的 docker

    docker -i -t ubuntu:15.10


- -t:在新容器内指定一个伪终端或终端。

- -i:允许你对容器内的标准输入 (STDIN) 进行交互

### 相关命令

- 显示容器详情

`docker ps`

    CONTAINER ID:容器ID
    NAMES:自动分配的容器名称

- `查看容器内的输出`

    docker logs CONTAINERID

- `停止容器运行`

    docker stop CONTAINERID 

### 高速下载合集

    http://get.daocloud.io/

### 命令合集

    docker search nginx
    docker pull nginx
    docker run --name runoob-nginx-test -p 8081:80 -d nginx
        -d 后台运行
        -p 将本地8081端口映射到80端口
    docker ps
        正在运行的容器
    docker cp 6dd4380ba708:/etc/nginx/nginx.conf ~/nginx/conf
        复制容器内容文件到本地
    docker run -d -p 8082:80 --name runoob-nginx-test-web -v ~/nginx/www:/usr/share/nginx/html -v ~/nginx/conf/nginx.conf:/etc/nginx/nginx.conf -v ~/nginx/logs:/var/log/nginx nginx
        映射容器内容的文件到本地
            不要用git bash 不然会出现找不到文件的错误，这是目录兼容符号的问题
    docker restart container-name
        重启容器  
            stop
            start
        
### 列出镜像

    docker image ls

    # 占用的空间
    docker system df 

### 虚悬镜像

    docker image ls -f dangling=true

    # 删除虚悬镜像
    docker image prune

### 中间层镜像

    docker image ls -a

### 列出部分镜像

    docker image ls ubuntu

    docker image ls ubuntu:18.04 

    # mongo:3.2 之后建立的镜像 | 之前：before 
    docker image ls -f since=mongo:3.2

    # 定义了label 的话，可以用
    docker image ls -f label=com.example.version=0.1

    docker image ls -q

    # 使用go模板语法格式化输出

    docker image ls --format "{{.ID}}: {{.Repository}}"

    5f515359c7f8: redis
    05a60462f8ba: nginx

    # 表格输出
    docker image ls --format "table {{.ID}}\t{{.Repository}}\t{{.Tag}}"

## 删除本地镜像

    # 镜像 镜像短id（3个字符）、长ID、镜像名、镜像摘要
    docker image rm [选项] <镜像1> [<镜像2> ...]

    # 镜像摘要显示
    docker image ls --digests

    # 配合删除
    docker image rm $(docker image ls -q redis

    
