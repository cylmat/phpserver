<?php

if (getenv('ZMQ_SERVER')) {
    $server = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REP);
    $server->bind("tcp://*:11111");
    
    while (true) {
        $message = $server->recv();
        $reply = strrev($message);
        $server->send($reply);
    }
    die();
} 

$ctx = new ZMQContext();
$req = new ZMQSocket($ctx, ZMQ::SOCKET_REQ);
$req->connect("tcp://localhost:11111");

$req->send("bonjour");
$response = $req->recv();
print("Réponse :  '$response'\n");
die();

// Nom d'hôte du serveur
$dsn = "tcp://localhost:11111";
// Crée un socket
$socket = new ZMQSocket(new ZMQContext(1), ZMQ::MODE_NOBLOCK, null);
// Envoi et réception
$socket->connect($dsn);
$socket->send("Test", ZMQ::MODE_NOBLOCK);
$message = $socket->recv(ZMQ::MODE_DONTWAIT);