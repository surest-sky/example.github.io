<?php 

function getUsers(){
    $names = ['小明', '小红', '小黑'];
    
    foreach($names as $name) {
        yield $name;
    }
}

# getUsers() 中 yield 构造出的是一个生成器
# 生成器是什么， 见 ex2.php

$generator = getUsers();

foreach($generator as $name) {
    echo $name;
}

echo $names; # 小明小红小黑
