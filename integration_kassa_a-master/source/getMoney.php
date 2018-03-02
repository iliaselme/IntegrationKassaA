<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Customer.php";

function getMoney($rfid)
{
    $url = "http://10.3.51.26:8069";
    $db = "odoo";
    $username = "dRRty@hotmail.be";
    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('x_rfid', '=', $rfid),
            array('active', '=', true))),
        array('limit' => 1));
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));

    foreach ($records as $record){
        return $record["x_money"];

    }

}

