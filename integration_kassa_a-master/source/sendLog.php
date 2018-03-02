<?php
require "send.php";
require_once "GetFromURL.php";
require_once "Error.php";
require_once "Customer.php";
require_once "Ack.php";
require_once "getOrderLines.php";

require_once('ripcord-master/ripcord.php');
$url = "http://10.3.51.26:8069";
$db = "odoo";
$username = "dRRty@hotmail.be";
$password = "test123";


$common = ripcord::client("$url/xmlrpc/2/common");
$uid = $common->authenticate($db, $username, $password, array());
$models = ripcord::client("$url/xmlrpc/2/object");

function sendError(LocalError $error){
    $arrayCust = array('MessageFrom' => $error->MessageFrom,'function'=>$error->function,'error'=> $error->error);
    $arrayMetadata = array('action' => 'create','type' => "error","sender" => "kassa", "id"=>getUuid(date("YmdHis")), "date" =>date(DATE_ISO8601), "params" => $arrayCust);


    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, 'logs');
}

function sendAck(LocalAck $ack){
    $arrayCust = array('MessageFrom' => $ack->MessageFrom,'function'=>$ack->function,'info'=> $ack->info);
    $arrayMetadata = array('action' => 'create','type' => "ack","sender" => "kassa", "id"=>getUuid(date("YmdHis")), "date" =>date(DATE_ISO8601), "params" => $arrayCust);


    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, 'logs');
}

function sendKeepAlive(){
    $arrayCust = array();
    $arrayMetadata = array('action' => 'create','type' => "ack_keepalive","sender" => "kassa", "id"=>getUuid(date("YmdHis")), "date" =>date(DATE_ISO8601), "params" => $arrayCust);


    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, 'logs');
}


function sendLogCustomerCreate($customer, $que){
    $arrayCust = array('uuid'=>$customer->uuid, 'firstName' => $customer->name,'lastName'=>$customer->x_last_name,'create_date'=> $customer->create_date,'address'=>$customer->city,'email'=>$customer->email,'version'=>getVersion(),'telephone'=>$customer->mobile, 'extras'=>"", 'breakfast'=>"0", 'payedAmount'=>"0");
    $arrayMetadata = array('action' => 'create','type' => "visitor","sender" => "kassa","id"=>$customer->uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);


    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, $que);
}
function sendLogReservation($reservation, $que){
    $arrayCust = array('uuid'=>$reservation->ref, 'firstname' =>$reservation->name,'lastname'=>$reservation->x_last_name,'address'=>$reservation->city,'email'=>$reservation->email,'version'=>getVersion(),'telephone'=>$reservation->mobile, 'extras'=>$reservation->extra, 'breakfast'=>$reservation->breakfast, 'payedAmount'=>$reservation->payedAmount);
    $arrayMetadata = array('action' => 'create','type' => "reservationvisitor","sender" => "kassa","id"=>$reservation->ref, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);

    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, $que);
}


function sendLogCustomerInactive($ref, $que){
    $arrayCust = array('uuid'=>$ref,'version'=>getVersion());
    $arrayMetadata = array('action' => 'update','type' => "visitor","sender" => "kassa" ,"block" => "false", "id"=>$ref, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);

    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, $que);
}

function sendLogCustomerBlock($customer, $que){
    $arrayCust = array('uuid'=>$customer->uuid,'version'=>getVersion());
    $arrayMetadata = array('action' => 'update','type' => "visitor","sender" => "kassa","block" => "true", "id"=>$customer->uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);

    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, $que);
}

function sendLogCustomerUnblock($customer, $que){
    $arrayCust = array('uuid'=>$customer->uuid, 'version'=>getVersion());
    $arrayMetadata = array('action' => 'update','type' => "visitor","sender" => "kassa","block" => "false", "id"=>$customer->uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);

    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, $que);
}


function sendLogCustomerUpdate($customer, $que){

    $arrayCust = array('firstName'=>$customer->name, 'lastName' => $customer->x_last_name,'address' => $customer->city,'telephone'=>$customer->mobile,'payedAmount'=> $customer->credit,'address'=>$customer->city,'email'=>$customer->email,'version'=>getVersion(),"extras"=>"",'breakfast'=>1,'payedAmount'=>0,'deleted'=>0,'registered'=>0,'blocked'=>0,'uuid'=>$customer->uuid);
    $arrayMetadata = array('action' => 'update','type' => "visitor","sender" => "kassa", "block" => "false", "id"=> $customer->uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);


    $jsoncustomer = json_encode($arrayMetadata);
    send($jsoncustomer, $que);
}


function sendLogOrderCreate($order,$que){


    $uuid = getUuid($order->id . date("YmdHis"));



    $lines = getOrderLine($order->id);

    $arrayCust = array('name' => $order->name, 'amount_paid' => $order->amount_to_pay, 'orderLines' => $lines, "create_date" => $order->create_date,'version'=>getVersion());
    $arrayMetadata = array('action' => 'create', 'type' => "order", "sender" => "kassa", "id" => $order->uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" => date("Y-m-d H:i:s"), "params" => $arrayCust);

    $jsonorder = json_encode($arrayMetadata);

    send($jsonorder, $que);

}

function sendLogOrderLineCreate($order,$que,$rfid){


    $uuid = getUuid($order->id);



    $lines = getOrderLine($order->id,$rfid);

    //$uuid,$product,$aantal,$prijs,$orderid,$userUuid
    foreach ($lines as $line)
    {
    $arrayCust = array("id" => $line->uuid , "productnaam" => $line->product,"aantal" => $line->aantal ,"prijs" => $line->price, "userUuid" => $line->userUuid, "version" => getVersion());
    $arrayMetadata = array('action' => 'create', 'type' => "orderline", "sender" => "kassa", "id" => $uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" => date("Y-m-d H:i:s"), "params" => $arrayCust);

    $jsonorder = json_encode($arrayMetadata);
    var_dump($jsonorder);

    send($jsonorder, $que);

}
}


function sendLogAccountMoney($saldo,$rfid,$que){

    $string = $saldo . $rfid . $que;
    $uuid = getUuid($string);

    $arrayCust = array('saldo' => $saldo,'rfid' => $rfid, "version" => getVersion());
    $arrayMetadata = array('action' => 'create','type' => "money","sender" => "kassa","id"=> $uuid, "username" => "KassaIT", "password" => md5("SpW3BBMf"), "date" =>date("Y-m-d H:i:s"), "params" => $arrayCust);
    $jsonorder = json_encode( $arrayMetadata);

    send($jsonorder,$que);
}




?>