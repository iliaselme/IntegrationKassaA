<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "createCustomer.php";
require_once "setInactiveCustomer.php";
require_once "UpdateCustomer.php";
require_once "GetFromURL.php";
require_once "createReservation.php";
require_once "Reservation.php";
require_once "blockCustomer.php";
require_once "Ack.php";
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('10.3.51.30', 5672, 'kassa', 'kassa');
$channel = $connection->channel();


echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
    $error = "";
    echo " [x] Received ", $msg->body, "\n";
    $object = json_decode($msg->body);
    if($object->type == "queue_keepalive"){
        sendKeepAlive();
    }
    else if($object->action=="create"){
        if($object->type=="visitor"){
            $uuid = getUuid($object->params->mail);
            if($uuid == "fail"){
                sendError(new LocalError("receive", "failed to get id", $object->sender));
                return;
            }
            $customer = new Customer(null,$object->params->firstname,$object->params->lastname,$object->params->create_date,$object->params->address,$object->params->mail,$object->params->telephone,$uuid,"");

            $error = createCustomer($customer);
            if(empty($error)) {
                sendAck(new LocalAck("receive", "customer is created", $object->sender));
            }
            else{
                sendError(new LocalError("createCustomer", $error, $object->sender));
            }
        }else if($object->type=="reservationvisitor"){
            //$name,$x_last_name, $create_date, $city, $email, $mobile,$ref,$breakfast,$extra,$payedAmount
            $reservation = new Reservation($object->params->firstName,$object->params->lastName,$object->date,$object->params->address,$object->params->email,$object->params->telephone,$object->id,true,"",0);
            $error = createReservation($reservation);
            if(empty($error)) {
                sendAck(new LocalAck("receive", "reservation is created", $object->sender));
            }
            else{
                sendError(new LocalError("createReservation", $error, $object->sender));
            }
        }
    }
    else if($object->action=="update"){
        if ($object->params->blocked == "1")
        {
            $customer = new Customer(null, $object->params->firstName, $object->params->lastName, $object->date, $object->params->address, $object->params->email, $object->params->telephone, $object->id, "", 0);
            $error = setBlock($customer);
            if(empty($error))
            {
                sendAck(new LocalAck("receive", "customer is blocked", $object->sender));
            }
            else
            {
                sendError(new LocalError("setBlock", $error, $object->sender));
            }
        }
        else
            {
            $customer = new Customer(null, $object->params->firstName, $object->params->lastName, $object->date, $object->params->address, $object->params->email, $object->params->telephone, $object->id, "", 0);
            $error = updateCustomer($customer->uuid, $customer);
            if(empty($error))
            {
                sendAck(new LocalAck("receive", "customer is updated", $object->sender));
            }
            else
            {
                sendError(new LocalError("updateCustomer", $error, $object->sender));
            }
        }
    }
    else if($object->action=="delete"){
        if($object->type=="visitor"){
            $id = $object->params->idVisitor;
            $error = setInactive($id);
            if(empty($error)) {
                sendAck(new LocalAck("receive", "customer is set inactive", $object->sender));
            }
            else{
                sendError(new LocalError("setInactive", $error, $object->sender));
            }
        }
    }
    else {
        echo "---message dropped---\n";
        sendError(new LocalError("receive", "message kon niet behandeld worden en is gedropt", $object->sender));
    }



};


$channel->basic_consume('kassa', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>