<?php

namespace App\Http\Api\Helpers;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Illuminate\Http\Exceptions\HttpResponseException;  # laravel 写法

# 如果是tp的话，这里 Use 一个tp的Http响应Exception方法即可
use Symfony\Component\HttpFoundation\JsonResponse;


Trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
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
    public function status($status, array $data, $code = null){

        if ($code){
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];

        $data = array_merge($status,$data);
        $this->respond($data);

    }

    /**
     * @param $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = '错误'){

        $this->setStatusCode($code)->message($message,$status);
    }

    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message, $status = "获取成功"){

        $this->status($status,[
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "服务器错误"){

        $this->failed($message,FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "创建成功")
    {
        $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $status = "获取成功"){

        $this->status($status,compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = '未找到')
    {
        $this->failed($message,Foundationresponse::HTTP_NOT_FOUND);
    }

    public function frobidden($message = '未授权')
    {
        $this->failed($message,Foundationresponse::HTTP_FORBIDDEN);
    }
}
