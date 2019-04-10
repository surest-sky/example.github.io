<?php

use Swoole\Http\Server;

$http = new Server("0.0.0.0", 9501);

// $http->on("request", function($request, $response) {
//     $data = $request->get;
//     $data = json_encode($data);
//     $response->end($data);
//     // $response->end("<h1>HelloServer</h1>");
// });

$http->set(
    [
        "enable_static_handler" => true,
        "document_root" => "/home/vagrant/example/web"
    ]
);

$http->on('request', function($request, $response) {
    $response->cookie('test', 'name', time() + 1800);
    $response->end("HttpServer");
});

$http->start();