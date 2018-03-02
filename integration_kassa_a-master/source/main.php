<?php
require_once "UpdateCustomer.php";
require_once "createCustomer.php";
require_once "setInactiveCustomer.php";
require_once "GetFromURL.php";
require_once "getAantal.php";
require_once "sendLog.php";
require_once "Error.php";
require_once "Ack.php";

sendAck(new LocalAck("main", "test ack", "kassa"));
sendError(new LocalError("main", "test error", "kassa"));

//echo getUuid(date("YmdHis"));
/*
$id = getUuid("aaaaaaafrt45");
if(!empty($id) && $id != "fail") {
  $customer = new Customer(1, "betalingtester", "de lol", date("Y-m-d H:i:s"), "Rue ddddd 58 1000", "rommel1@rommel.com", "03494l299339", $id, "5555", 10);
//$id = getId($customer->email);
    createCustomer($customer,true);
    //$customer->id = searchId();
    //updateCustomer($customer->uuid, $customer);
    //setInactive($id);
}
else{
    echo "fail";
}
*/
