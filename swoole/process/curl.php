<?php

$urls = [
    "https://www.baidu.com",
    "https://wiki.swoole.com",
    "https://taobao.com",
    "https://qq.com",
    "https://wiki.swoole.com",
    "https://github.com"
];

$workers = [];

for ($i=0; $i < 6; $i++) { 
    $process = new swoole_process(function(swoole_process $process) use ($i, $urls){
        $content = curl_data($urls[$i]);
        // $process->write($content);
        echo $content;
    }, true);

    $pid = $process->start();

    $workers[$i] = $process;
}


foreach($workers as $worker){
    echo $worker->read();
}

function curl_data($url) {
    return file_get_contents($url);
}