<?php 

function getUsers(){
    $names = ['小明', '小红', '小黑'];
    
    foreach($names as $key => $name) {
        yield $key => $name;
    }
}

$generators = getUsers();


foreach($generators as $generator) {
    echo $generators->current(); # 当前迭代的内容
    $generators->next();  # 替代遍历，手动执行一次
    echo $generators->key(); # 迭代器的key
    // $generators->rewind(); 
    # 重置迭代器 , 它会将迭代器跑一遍，我们可以在使用迭代器之前用于对数据进行测试，捕获错误行为
    # 如果迭代器正在运行，将会抛出错误
    echo $generators->current();
}