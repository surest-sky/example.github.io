<?php

namespace App\Http\Api\Helpers;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;


Trait ApiResponse
{
    /**
     * @var int
     */
    public $code = FoundationResponse::HTTP_OK;

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
        $response = JsonResponse::create($data,$this->getStatusCode(),$header);

        throw new HttpResponseException($response);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return mixed
     */
    public function status($msg, array $data, $code = null, $errcode = null){

        if ($code){
            $this->setStatusCode($code);
        }

        $status = [
            'msg' => $msg,
            'code' => $errcode ?? ''
        ];

        $data = array_merge($status,$data);
        $this->respond($data);

    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "服务器错误", $errcode = 5000)
    {
        $this->status($message, [],  FoundationResponse::HTTP_INTERNAL_SERVER_ERROR, $errcode);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "创建成功")
    {
        $this->status($message, [],  FoundationResponse::HTTP_CREATED);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $message = "success"){

        $this->status($message,compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = '未找到')
    {
        $this->status($message, [],  Foundationresponse::HTTP_NOT_FOUND, 4004);
    }

    public function frobidden($message = '未授权', $errcode = 4001, $code = 401)
    {
        $this->status($message, [],  $code, $errcode);
    }

    public function failed($message = '授权失败', $errcode = 4003, $code = 403)
    {
        $this->status($message, [],  $code, $errcode);
    }


}
