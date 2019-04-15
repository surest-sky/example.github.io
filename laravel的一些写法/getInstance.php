<?php

    static $instance;

    /**
     * 单例模式获取
     * @return YimServer
     */
    public static function getInstance()
    {
        if( is_null(self::$instance) ) {
            return new self();
        }

        return self::$instance;
    }
