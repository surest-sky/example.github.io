## 在typecho中使用七牛云上传图片

目前存在隐患和不足的地方，请注意

- 无需鉴权即可获取 七牛云 上传凭证

- AK+SK需要在代码中进行配置


## 使用条件

php > 7.0  
会一点composer（不会也没事）
composer 环境， 具体如何安装composer， 这里不作赘述

## 安装七牛云 SDK

进入项目根目录

创建包目录
创建composer.json

    vi composer.json
    >   

    {
        "name": "root/surest_typecho",
        "authors": [
            {
                "name": "RA31",
                "email": "ar13133@163.com"
            }
        ],
        "require": {
            "qiniu/php-sdk": "^7.2"
        }
    }

执行 `composer install`


## 导相关包

`admin/auth` 下放置这个文件

`tools` 目录下放置这个文件

进入`admin/write-post.php` ， 插入语句

在 z最后一行插入 `<?php include_once '../tools/qiniu_upload.php'?>`


## 使用

进入编辑器， 复制粘贴， 图片即可

复制粘贴上传七牛玉只支持

- 微信复制图片

- qq 截图等等



## 提示

相关的配置 AK 和 SK 用的是我的密钥，建议自己更改，这个仅做测试使用


## 配合添加配置文件

修改AK和SK

进入 `auth/qiniu.php` 中

修改 `$config` 配置

进入 `tools/qiniu_upload.php`

    const DOMAIN = 'http://cdn.surest.cn/'  // cdn 域名
    const URL = "http://surest.cn/admin/auth/qiniu.php" // 例如 将 http://surest.cn/admin 修改为 你的博客后台访问地址

## 完成

进入编辑器 ， 复制粘贴即可