<?php

class A {
    protected $ioc;

    public function __construct(Ioc $ioc)
    {
        $this->ioc = $ioc;
    }

    public function getB()
    {
        return $this->ioc->make('b');
    }
}

class B
{
    protected $ioc;

    public function __construct(Ioc $ioc)
    {
        $this->ioc = $ioc;
    }
}


class Ioc {
    // 存储容器
    protected $instances = [];

    public function __construct()
    {
        // 将容器保存起来
        $this->instances['a'] = new A($this);
        $this->instances['a'] = new B($this);
    }

    public function make(string $abstract)
    {
        // 获取容器
        return $this->instances[$abstract];
    }
}


// 控制反转










