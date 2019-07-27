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
    
    $ioc = new Ioc();
    $a = $ioc->make('a');
    $b = $a->getB();
    var_dump($b);

流程

注册一个IOC容器
将需要注入的类（子类）， 保存到容器中
子类在注入的时候，获取到了容器实例
子类便可以拿到父类的容器，从来达到控制 其他容器中的类的子类的条件

控制反转是站在 A 的立场来看的，它是拿 B 的。

依赖注入是站在 IoC 的立场来看的，它是送 B 的

控制反转通过其他方式获取子类控制
依赖注入是传递控制子类









