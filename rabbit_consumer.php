<?php 

//config 
$conn_args = array(
    'host' => '10.11.8.202',
    'port' => '5672',
    'login' => 'admin',
    'password' => 'admin',
    'vhost'=>'/' 
);
$e_name = 'e_linvo'; // exchange name
$q_name = 'q_linvo'; // query name
$k_route = 'key_2'; // route key

// create connection & channel
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);

// create exchange
$ex = new AMQPExchange($channel);
$ex->setName($e_name);
$ex->setType(AMQP_EX_TYPE_FANOUT); //direct type
$ex->setFlags(AMQP_DURABLE); // 
echo "Exchange Status:".$ex->declareExchange()."\n";

// create queue
$q = new AMQPQueue($channel);
$q->setName($q_name);
$q->setFlags(AMQP_DURABLE); //
echo "Message Total:".$q->declareQueue()."\n";

// bind
echo 'Queue Bind: '.$q->bind($e_name, $k_route)."\n";

echo "Message:\n";
while(True){
    $q->consume('processMessage');
    //$q->consume('processMessage', AMQP_AUTOACK); // ack
}
$conn->disconnect();

/**
 * callback
 */
function processMessage($envelope, $queue) {
    $msg = $envelope->getBody();
    echo $msg."\n"; // bussiness logic
    $queue->ack($envelope->getDeliveryTag()); // ack manual
}