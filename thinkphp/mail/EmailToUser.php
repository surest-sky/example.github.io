<?php
/**
 * Created by PhpStorm.
 * User: chenf
 * Date: 19-7-4
 * Time: 下午2:22
 */

namespace app\common\server;

use app\admin\model\AdminUser;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

/**
 * 邮件通知管理员
 * Class EmailToUser
 * @package app\common\server
 */
class EmailToUser
{
    const list_key = 'd88_email_notice';

    /**
     * 写入等待发送的邮件信息
     */
    public static function write_message($message)
    {
        # 写入redis , 防止产生异常问题报错
        try {
            $redis = redis_connect(3);
            $redis->lPush(self::list_key, $message);
        }catch (\Exception $e) {
            \think\facade\Log::error("严重异常报告: " . $e->getMessage());
        }
    }

    /**
     * 获取定时任务邮件发送通知
     */
    public static function send_messages()
    {
        try {
            $redis = redis_connect(3);
            while ($message = $redis->lPop(self::list_key)) {
                self::send_to("d88邮件通知", $message);
            }
        }catch (\Exception $e) {
            \think\facade\Log::error("严重异常报告: " . $e->getMessage());
        }
    }

    /**
     * 发送邮件入口
     */
    public static function send_to($title, $body)
    {
        try {
            $host = config('email.username');
            $mail = new Message();
            $mail->setFrom("{$host}", "D88科技")
                ->setHeader("name", $host);

            $mail= self::toMailUser($mail)
                ->setSubject($title)
                ->setBody($body);

            $mailer = self::getMailer();
            $mailer->send($mail);
        }catch (\Exception $exception) {
            \think\facade\Log::error("严重异常: " . $exception->getMessage());
        }

    }

    /**
     * 用户推送
     * @param $mail
     * @return mixed
     */
    public static function toMailUser($mail)
    {
        $amdin_user = AdminUser::where('isNotice', 1)->whereNotNull('email')->field(['email'])->all();
        $emails = array_pluck($amdin_user, 'email');

        foreach ($emails as $email) {
            $mail->addTo($email);
        }

        return $mail;
    }
}