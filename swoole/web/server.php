<?php

$http = new swoole_http_server("0.0.0.0", 9503); # 0.0.0.0 表示所有的ip地址

/**
 * $request对象，包含了请求的相关信息，如GET/POST请求的数据
 * $response对象，对request的响应可以通过操作response对象来完成。$response->end()方法表示输出一段HTML内容，并结束此请求。
 * 
 */
$http->on('request', function($request, $response) {
    var_dump($request->get, $request->post);

    $response->header('Content-Type', 'text/html', 'charset=utf-8');
    $response->end("<h1>Hello Swoole" . rand(100, 9999) . "</h1>\n");
});

# 以下操作是应对响应两次请求
$http->on('request', function($request, $response){
    if( $request->server(['path_info'] == '/favicon.ico') || $request->server['request_uri'] == '/favicon.ico'){
        return $response->end();
    }
});

$http->start();
/**
 * 0.0.0.0 表示监听所有IP地址，一台服务器可能同时有多个IP，如127.0.0.1本地回环IP、192.168.1.100局域网IP、210.127.20.2 外网IP，这里也可以单独指定监听一个IP
 * 9501 监听的端口，如果被占用程序会抛出致命错误，中断执行。
 */