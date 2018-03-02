<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "order.php";

function getAllOrder()
{
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $ids = $models->execute_kw($db, $uid, $password,
        'pos.order', 'search',
        array(array(array( 1, "=" ,1),
        ))
    );
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order', 'read', array($ids));

    //function __construct($id,$naam , $date, $credit, $amountPaid){
    $arrayorder = array();
    foreach ($records as $record){
        array_push($arrayorder, new Order($record["id"], $record["name"], $record["create_date"], $record["lines"], $record["amount_paid"]));
    }

    return $arrayorder;
}

