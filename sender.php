<?php
//publishes messages
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try{
 //establish the connection to an AMQP server
 $connection = new AMQPStreamConnection('192.168.2.3', 5672, 'admin', '12345');
 $connection->connect();
 
 if(!$connection->isConnected()){
  die('connection error');
 }

 $channel = new AMQPChannel($connection);
 if(!$channel->isConnected()){
  die('Connection through channel failed!');
 }

//set exchange
 $exchangeName= 'stream';
 $exchange    = new AMQPExchange($channel);
 $exchange->setName($exchangeName);

//set queue
 $queueName  = '490p';
 $queue      = new AMQPQueue($channel);
 $queue->setName($queueName);
 $routingKey  = '';

//publish message
 $message = "Hello world!";
 if($exchange->publish($message, $routingKey)){
  echo 'Published!';
 }
}catch(AMQPException $e){
 echo 'AMQP Exception - '.$e->getMessage();
}catch(AMQPConnectionException $e){
 echo 'AMQP Connection Exception - '.$e->getMessage();
}catch(AMQPExchangeException $e){
 echo 'AMQP Exchange Exception - '.$e->getMessage();
}catch(AMQPQueueException  $e){
 echo 'AMQP Queue Exception - '.$e->getMessage();
}
