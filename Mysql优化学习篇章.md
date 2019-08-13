# Mysql优化汇总篇章

## 参考文档

1) MYSQL性能优化系统整理 : [https://shuwoom.com/?p=3347](https://shuwoom.com/?p=3347)

2) explian解读-1: https://github.com/XiaoMi/soar/blob/master/doc/explain.md

3) explian解读-2: https://www.cnblogs.com/xuanzhi201111/p/4175635.html

## 相关术语解释

## 垂直拆分 (纵向)

1) 参考1 : https://baijiahao.baidu.com/s?id=1607944199335196177&wfr=spider&for=pc

数据库端按照业务垂直拆分: 按照业务交易数据库、用户数据库、商品数据库、店铺数据库等进行拆分。 

为什么: 在数据量庞大的情况下，单机跑一个库，容易造成负载

2) 优点

1. 拆分后业务清晰，拆分规则明确。
2. 系统之间整合或扩展容易。
3. 数据维护简单。

３) 缺点：
    1. 部分业务表无法join，只能通过接口方式解决，提高了系统复杂度。
    2. 受每种业务不同的限制存在单库性能瓶颈，不易数据扩展跟性能提高。
    3. 事务处理复杂。
    
## 水平拆分 (横向)

在单库中也可能数据庞大，单机瓶颈，　采用水平拆分

常见的例如:　根据时间拆分, 根据数据量拆分

文章参考描述

水平拆分，总之，一般先分库，如果分库后查询仍然慢，于是按照分库的思想开始做分表的工作数据库采用分布式数据库（所有节点的数据加起来才算是整体数据），
文件系统采用分布式文件系统任何强大的单一服务器都满足不了大型系统持续增长的业务需求，
数据库读写分离随着业务的发展最终也将无法满足需求，
需要使用分布式数据库及分布式文件系统来支撑


## InnoDB还是Mysiam

1.MyISAM查询性能比InnoDB更快，但不支持事务处理，InnoDB支持事务处理和外键等高级功能
2.InnoDB不支持全文检索
3.InnoDB中不保存表的具体行数，也就是说，执行`select count(*) from table`时，InnoDB要扫描一遍整个表来计算有多少行，但是MyISAM只要简单的读出保存好的行数即可。注意的是，当count(*)语句包含 where条件时，两种表的操作是一样的。
4.DELETE FROM table时，InnoDB不会重新建立表，而是一行一行的删除
5.Innodb的auto_increment字段，必须建立单独的索引，而不允许是联合索引
6.每张MyISAM 表被存放在三个文件 ：frm 文件存放表格定义。 数据文件是MYD (MYData) 。 索引文件是MYI (MYIndex) 引伸。
因为MyISAM相对简单所以在效率上要优于InnoDB，小型应用使用MyISAM是不错的选择。
MyISAM表是保存成文件的形式,在跨平台的数据转移中使用MyISAM存储会省去不少的麻烦
InnoDB 把数据和索引存放在表空间里，可能包含多个文件，这与其它的不一样，举例来说，在 MyISAM 中，表被存放在单独的文件中。InnoDB 表的大小只受限于操作系统的文件大小，一般为 2 GB。InnoDB所有的表都保存在同一个数据文件 ibdata1 中（也可能是多个文件，或者是独立的表空间文件），相对来说比较不好备份

## mysql 索引

- 主键索引 primary key

- 普通索引 normal key

- 唯一索引 unique key

- 联合索引

    坚持最左前缀原则 select a where status = 1 and type = 2 and name ='xiaom' 
    匹配 (status, type) (status)
    不匹配 (status, name)

- 全文索引 Full Text

    InnoDB 不支持

## 查询优化

**COUNT(*)**

只有没有任何WHERE条件的COUNT(*)才非常快

**不使用`select*`**

查询不需要的记录、多表关联时返回全部列、总是取出全部列（select * from ….）、重复查询相同的数据。这些都会给MySQL服务器带来额外的负担，并增加网络开销，另外也会消耗应用服务器的CPU和内存资源。

**切分查询**

- 让缓存的效率更高。
- 将查询分解后，执行单个查询可以减少锁的竞争。
- 在应用层做关联，可以更容易对数据库进行拆分，更容易做到高性能和可扩展。
- 查询效率也有可能会有所提升。
- 可以减少冗余记录的查询。
- 更进一步，这样做相当于在应用层中实现了哈希关联，而不是是使用MySQL的嵌套循环关联。

**添加合适的索引**


## 索引优化过程

环境使用的是`thinkphp`

首先开启 sql 日志

请求接口查看sql语句

![图片描述...](http://cdn.surest.cn/FtbF5SESVRsb90jzYV2C9iPgLcQw)

### 过滤掉重复的查询

例如在获取器查询语句中需要查询城市的信息。 通过缓存的方式处理

    .....
    public static function getAreaName($id)
    {
        $key = 'area';
        $redis = redis_connect(3);
        $areas = $redis->get($key);
        if(!$areas) {
            $areas = Area::cacheTo($redis, $key);
        }

        $areas = unserialize($areas);

        return $areas[$id] ?? '未知';
   ...

    /**
     * 缓存类型 , 并且返回数据
     */
    public static function cacheTo($redis, $key)
    {
        if($result = self::select()){
            # 拼装成key - value 格式
            $result = $result->column('name', 'id');
            $result = serialize($result);
            $redis->set($key, $result);
            return $result;
        }

        return serialize([]);
    }

因此，调用方式如下

    public function getAreaNameAttr($value, $data)
    {
        return AreaCache::getAreaName($data['area_id']);
    }

### 未完待续