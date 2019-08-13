# laravel - 配置http服务器

>  教程来源: https://laravelacademy.org/post/9801.html

# 开发环境

    deepin + mysql5.6 + php7.1

# 安装 + 部署

    # 配置一个laravel, 并且,设置host为
    http://swoole.laravel.test

    # ab 测试
    sudo apt-get install apache2-utils

    # 获取测试结果
    ab -n 100 -c 10 http://swoole.laravel.test

# 

-n1000 表示总请求数位1000
-c 表示并发用户数为10
Server Software Web服务器软件名称
Server Hostname URL中的主机部分名称
Server Port Web服务器软件的监听端口
Document Path URL中根绝对路径
Document Length http响应数据的正文长度。
Concurrency Level 设置的参数
Time taken for tests 处理完成花费的总时间
Complete requests 表示总请求数，这是我们设置的相应参数。
Failed requests 失败的请求数
Total transferred 响应数据长度总和
HTML transferred 正文数据的总和
Requests per second 吞吐率
    Complete requests / Time taken for tests
Time per request 用户平均请求等待时间
Time taken for tests 平均请求处理时间
Transfer rate 单位时间内从服务器获取的数据长度

