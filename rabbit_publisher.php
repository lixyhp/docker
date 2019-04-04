<?php

//config
$conn_args = array(
    'host' => '10.11.8.202',
    'port' => '5672',
    'login' => 'admin',
    'password' => 'admin',
    'vhost'=>'/'
);
$e_name = 'e_linvo'; //exchange
//$q_name = 'q_linvo'; //queue
$k_route = 'key_1'; //route key

//create connection & channel 
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);



//create exchange   
$ex = new AMQPExchange($channel);
$ex->setName($e_name);
//send message
//$channel->startTransaction(); // transaction
for($i=0; $i<5; ++$i){
    sleep(3);
    //message
    $message = "TEST MESSAGE".$i.' '.date("h:i:sa");
    echo "Send Message:".$ex->publish($message, $k_route)."\n";
}
//$channel->commitTransaction(); //commit

$conn->disconnect();