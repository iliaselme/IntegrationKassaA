<?php
require_once "sendLog.php";
require_once "Customer.php";

function createCustomer($customer){
    require_once('ripcord-master/ripcord.php');
    $url = "http://10.3.51.26:8069";
    $db = "odoo";
    $username = "dRRty@hotmail.be";
    $password = "test123";
    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");
    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('email', '=', $customer->email),
            array('customer', '=', true))),
        array('limit'=>1));
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));
// count the number of fields fetched by default

    if(count($records)==0){
        $arrayCust = array('previous_id' => "0");
        $json = json_encode($arrayCust);
        $id = $models->execute_kw($db, $uid, $password,
            'res.partner', 'create',
            array(array('name'=>$customer->name,'x_last_name'=>$customer->x_last_name,'create_date'=> $customer->create_date,'city'=>$customer->city,'email'=>$customer->email,'mobile'=>$customer->mobile,'ref'=>$customer->uuid,'comment'=>$json,'x_reservation' => 0, 'x_rfid' => $customer->rfid, 'x_money' => $customer->credit)));
        if(isset($id)){
            //sendLogCustomerCreate($customer, "CRM");
            sendLogCustomerCreate($customer, "monitoring");
        }
    }else{
        echo "Customer bestaat al";
        return "Customer already exists.";
    }
}
?>