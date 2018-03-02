<?php

require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Customer.php";

function setBlock($customer){

    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");



    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('ref', '=', $customer->uuid),
            array('active', '=', true),)),
        array('limit'=>1));

    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));

    if(empty($records) || isset($results["faultCode"])){
        return"customer doesn't exist.";
    }

    else {
    foreach($records as $record){
        $id = $record['id'];
        $control = $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
            array(array($id), array('x_blocked'=>1)));
        if(isset($control)){
            $temp = getCustomerByUuid($customer->uuid);
            sendLogCustomerBlock($temp, "monitoring");
            sendLogCustomerBlock($temp, "CRM");
        }
    }
    }
}




function setUnblock($customer){

    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");



    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('ref', '=', $customer->uuid),
            array('active', '=', true),)),
        array('limit'=>1));

    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));

    if(empty($records)){
        return"customer doesn't exist.";
    }
    foreach($records as $record){
        $id = $record['id'];
        $control = $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
            array(array($id), array('x_blocked'=>0)));
        if(isset($control)){
            $temp = getCustomerByUuid($customer->uuid);
            sendLogCustomerBlock($temp, "monitoring");
        }
    }
}



?>
