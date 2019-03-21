<?php

# 构建 tcp socket
# https://wiki.swoole.com/wiki/page/26.html
$client = new swoole_client(SWOOLE_SOCK_TCP);

if( !$client->connect("127.0.0.1", 9501) ) {
    echo '连接失败';
    die;
}

fwrite(STDOUT, '请输入');

$msg = trim(fgets(STDIN));

# 失败返回false，并设置$swoole_client->errCode
$client->send($msg);

$result = $client->recv();

echo $result . PHP_EOL;

