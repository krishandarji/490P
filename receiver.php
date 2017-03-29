<?php
//consumes messages
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try{
 //establish the connection to an AMQP server
 $connection = new AMQPStreamConnection('192.168.2.3', 5672, 'admin', '12345');
 $connection->connect();

 if(!$connection->isConnected()){
  die('connection failed!');
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

 $counter = 0;
 while($envelope = $queue->get()){
  //get message payload
  $message = $envelope->getBody();
  if($message){
   echo $message.'';
   //inform the queue that the message was acknowledged
   $queue->ack($envelope->getDeliveryTag());
  }else{
   $queue->nack($envelope->getDeliveryTag(), AMQP_REQUEUE);
  }
 
  $counter++;
 }
 
 if($counter){
  echo 'Consuming...';
 }else{
  echo 'No messages to consume...';
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

