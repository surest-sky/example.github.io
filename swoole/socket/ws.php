<?php

class Ws {
    const HOST = "0.0.0.0";
    const PORT = 9505;
    public $ws = null;

    public function __construct() 
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->set(
            [
                "worker_num" => 2,
                "task_worker_num" => 4
            ]
        );

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on('task', [$this, "onTask"]);
        $this->ws->on('finish', [$this, "onFinish"]);
        $this->ws->on("close", [$this, 'onClose']);
        $this->ws->start();
    }

    /**
     * 监听打开事件
     */
    public function onOpen($ws, $request) {
        $ws->push($request->fd, "hello welcome");
    }

    /**
     * 处理异步任务
     */
    public function onTask($ws, $task_id, $from_id, $data)
    {
        print_r($data);

        sleep(10);

        $ws->finish("OK");
    }

    /**
     * 处理异步任务的结果
     */
    public function onFinish($ws, $task_id, $data)
    {
        echo json_encode($data);
    }

    /**
     * 监听消息事件
     */
    public function onMessage($ws, $frame)
    {
        echo "Message: {$frame->data}\n";
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        $time = date('Y-m-d H:i:s', time());

        # 执行定时任务
        swoole_timer_tick(2000, function() use ($data, $ws) {
            $ws->push($data['fd'], "tick - 2000ms\n");
            echo "tick - 2000ms\n";
        });

        swoole_timer_after(6000, function() {
            echo '我执行完成了';
        });

        $ws->task($data);
        $ws->push($frame->fd, "server: {$time}");
    }

    /**
     * 关闭事件
     */
    public function onClose($ws, $fd)
    {
        echo "clien-{$fd} is closed";
    }
}

new Ws();