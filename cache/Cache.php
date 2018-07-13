<?php
/**
 * Created by PhpStorm.
 * User: dcf
 * Date: 2018/7/12
 * Time: 17:38
 */

header("Content-type:text/html;charset=utf-8");
/**
 * Class Connect 单例模式链接数据库
 */
Class Connect
{
    static protected $_instance;
    protected $mysql = [
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'homestead',
        'pwd'  => 'secret',
        'table'=> 'grade'
    ];

    public function getInstance()
    {
        if( !self::$_instance instanceof self ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function link( $name )
    {
        // 检查 缓存是否存在
        if( $cache = $this->checkCache($name) ){
            echo '获取缓存';
            return $cache;
        }
        $sql = sprintf("SELECT * FROM data where name = '%s';" , $name);
        $mysql = $this->mysql;
        $link = mysqli_connect( $mysql['host'] ,$mysql['user'] , $mysql['pwd'] , $mysql['table'] , $mysql['port']);
        if( !$link ){
            exit( 'mysql-error:' . mysqli_error($link));
        }
        mysqli_set_charset($link , 'utf-8');
        $r = mysqli_query($link , $sql);
        if( !$r ){
            exit( 'mysql-error:' . mysqli_error($link ));
        }
        $r =  mysqli_fetch_all($r , MYSQLI_ASSOC);

        // 写入缓存
        echo '写入缓存' . $name .'的数据';
        (new Cache())->cacheData($name , $r , 3600);
        return $r;
    }

    protected function checkCache( $name ){
        if( empty($name) ){
            return false;
        }

        return (new Cache())->cacheData($name);
    }
}



class Cache
{
    protected $_dir;
    const EXT = '.txt';

    public function __construct()
    {
        $this->_dir = dirname(__FILE__);
    }
    /**
     * @param string $key   储存缓存文件的名称
     * @param string $value 需要存储的内容  | 为空时 表示获取该数据信息 | 为null 时 表示删除数据
     * @param string $path  存储的地址 | 默认
     */
    public function cacheData( $key='' , $value='' , $cacheTime=180 )
    {
        $result = false;

        $filename = $this->_dir .'/file' . DIRECTORY_SEPARATOR . $key . self::EXT;
        if( empty($key) ){
            return false;
        }

        // 写入缓存
        if( !is_null($value) && !empty($value)  ){
            $dir = dirname( $filename );
            if( !is_dir($dir) ){
                mkdir( $dir , 0777);
            }
            $cacheTime = sprintf( "%011d" , $cacheTime);
            $startime = sprintf( "%011d" , time());
            $result = file_put_contents( $filename , $cacheTime.$startime.json_encode($value) );

        }else{

            if( !is_file( $filename ) ){
                return false;
            }
            if( is_null($value) ){
                $result = unlink($filename);
            }elseif( empty($value) ){ // 获取缓存数据

                // 判断缓存是否 过期
                $result = file_get_contents( $filename );
                $expires =  intval(substr($result , 0,11));  // 保存的时间
                $startime = intval(substr($result , 11 , 11)); // 开始时间

                // 时间过期的话 删除文件， 重新获取缓存
                if( ($expires + $startime) < time() ){
                    echo '删除文件';
                    unlink($filename);
                    return false;
                }else{
                    $value = substr($result , 22);
                    return json_decode($value , true);
                }
            }
        }

        if( $result ){
            return true;
        }else{
            return false;
        }

    }
}