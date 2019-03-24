<?php

$ws = new swoole_websocket_server("0.0.0.0", 9502);

// 打开连接事件
$ws->on('open', function($ws, $request) {
    var_dump($request->fd, $request->get, $request->server);

    $ws->push($request->fd, "hello word\n");
});


// 监听websocket事件
$ws->on('message', function($ws, $frame) {
    echo "message : {$frame->data}";

    $ws->push($frame->fd, "server: {$frame->data}");
});

//
$ws->on('close', function($ws, $fd) {
    echo "client-{$fd} is clesed";
});

$ws->start();