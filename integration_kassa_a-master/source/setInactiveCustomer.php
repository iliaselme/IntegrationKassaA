<?php

require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Customer.php";

function setInactive($customerId){

    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    /*
    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('id', '=', $customerId),
            array('active', '=', true))),
        array('limit'=>1));*/
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($customerId));

    if(empty($records)){
        return "customer doesn't exist.";
    }




        $control = $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
            array(array($customerId), array('active'=> false)));
        if(isset($control)){
            //sendLogCustomerInactive($ref, "monitoring");
            //sendLogCustomerInactive($ref, "CRM");

        }
}

?>
