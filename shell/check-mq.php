<?php

if (1==getenv('SERVER')) {
    $server = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REP);
    $server->bind("tcp://*:11111");
    
    while (true) {
        $message = $server->recv();
        $reply = strrev($message);
        $server->send($reply);
    }
    die();
}

$req = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REQ);
$req->connect("tcp://localhost:11111");

$req->send("hello");
$response = $req->recv();
if ($response == "olleh") {
    print("Zmq server: [OK]\n");
} else {
    exit(1);
}