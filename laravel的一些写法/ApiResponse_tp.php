<?php

namespace app\common\controller\Helpers;

use think\exception\HttpException;
use think\Response;
use \think\exception\HttpResponseException;

Trait ApiResponse
{
    /**
     * @var int
     */
    public $code = 200;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->code = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data, $header = [])
    {
        if( $this->request->isAjax() ) {
            $type = 'json';
        }else{
            if( is_array($data) ) {
                $data = json_encode($data);
            }
        }

        $type = $this->getResponseType();

        $response = Response::create($data, $type, $this->code, $header);

        throw new HttpResponseException($response);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return mixed
     */
    public function status($msg, array $data, $code = null, $errcode = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }
        $status = [
            'msg' => $msg,
            'code' => $errcode ?? ''
        ];
        $data = array_merge($status, $data);
        $this->respond($data);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "服务器错误", $errcode = 5000)
    {
        $this->status($message, [], 500, $errcode);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "创建成功")
    {
        $this->status($message, [], 201);
    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success_($data, $message = "success")
    {
        $this->status($message, compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = '未找到')
    {
        $this->status($message, [], 404, 4004);
    }

    public function frobidden($message = '未授权', $errcode = 4001, $code = 401)
    {
        $this->status($message, [], $code, $errcode);
    }

    public function failed($message = '授权失败', $errcode = 4003, $code = 403)
    {
        $this->status($message, [], $code, $errcode);
    }
}
