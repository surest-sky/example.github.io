<?php

# 使用平常的方法写入1万条数据
$content = "使用平常的方法写入1万条数据\r\n";

$stime = microtime(true);
$smemory = memory_get_usage();

$i = 0;
$handle = fopen(__DIR__ . '/test.txt', 'r');

function handler($handle){
    while( !feof($handle) ) {
        $c =  fgets($handle);
        yield $c;
    }
}

$data = handler($handle);

foreach($data as $value) {
    echo $value;
}

fclose($handle);

$etime = microtime(true);
$ememory = memory_get_usage();

$runtime = round(($etime - $stime), 3);  # 运行时间
$memory_usage = $ememory - $smemory; # 消耗的内存

echo "运行时间" . $runtime . " \r\n" . "消耗的内存" . $memory_usage . " \r\n";