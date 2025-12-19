<?php
$server = new Swoole\Http\Server("0.0.0.0", 8003);

$server->on("request", function ($request, $response) {
    $response->end("Hello, World!\n");
});

$server->start();