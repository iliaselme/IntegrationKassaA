<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

function send($message, $receiver){

    $connection = new AMQPStreamConnection('10.3.51.30', 5672, 'kassa', 'kassa');
    $channel = $connection->channel();

    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', $receiver);

    echo " [x] Sent " .$message . "\n";

    $channel->close();
    $connection->close();

}