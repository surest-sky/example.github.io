<?php
/**
 * Created by PhpStorm.
 * User: dcf
 * Date: 2018/7/12
 * Time: 17:38
 */



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
    public function cacheData( $key='' , $value='' ,  $path = '/file' )
    {
        $result = false;

        $filename = $this->_dir . $path . DIRECTORY_SEPARATOR . $key . self::EXT;
        if( empty($key) ){
            return false;
        }

        // 写入缓存
        if( !is_null($value) && !empty($value)  ){
            $dir = dirname( $filename );
            if( !is_dir($dir) ){
                mkdir( $dir , 0777);
            }
            $result = file_put_contents( $filename , json_encode($value) );

        }else{

            if( !is_file( $filename ) ){
                return false;
            }
            if( is_null($value) ){  // 获取缓存数据
                $result = unlink($filename);
            }elseif( empty($value) ){
                $result = file_get_contents( $filename , true );
                return $result;
            }
        }

        if( $result ){
            return 'success';
        }else{
            return 'error';
        }


    }
}