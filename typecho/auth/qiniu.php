<?php

require_once '../../vendor/autoload.php';
require_once './config.php';

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

// 这个文件放在根目录下的  admin/auth 下, auth 需要创建目录

$config = [
    'AK' => 'uLP4c_XV5CGCzxmlUOcenQu3ik0fhpglqJ0BXi5P', // 必须
    'SK' => 'ASvNGzihVmm_iDv5mHeq3gGspucEy8VsrJT90QWp', // 必须
    'bucket' => 'my-image' // 必须
];

$upManager = new UploadManager();
$auth = new Auth($config['AK'], $config['SK']);
$token = $auth->uploadToken($config['bucket']);

echo json_encode(compact('token'));