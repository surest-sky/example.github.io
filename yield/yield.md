## php 中 yield 的使用

首先我们带着几个问题来找答案

- yild 是什么

`yield` 在使用场景上，直观的查看代码，和 `return` 有点像。 实际他们是截然不同的东西，
它是一个生成器， 只有在你调用他的时候才会执行，并不产生多余的值，这个东西放到我们下面来讲


- 和`yield`有关的东西是什么

`Generator: http://php.net/manual/zh/class.generator.php`

话不多说太多， 别人写的或许比我更透彻

`https://www.cnblogs.com/zuochuang/p/8176868.html`


相关的例子：

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


------------------

ex2.php


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



read.php

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


总之， `yield` 强调的是用即调之，例如读取文件的时候不需要提前去内存取出这些数据，再一一获取。 而`yield`则解决了这些问题，他只记录一条信息，那就是你执行到哪儿了。 再根据你的位置进行下一步


代码示例地址 ： https://github.com/surest-sky/example/tree/master/yield