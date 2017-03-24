<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'admin', '12345');
$channel = $connection->channel();

$channel->queue_declare('490p', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, 'stream', '490p');

//echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();

?>
