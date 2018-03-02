<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Orderline.php";
require_once "GetFromURL.php";
require_once "getCustomer.php";



function getOrderline($orderid,$rfid)
{
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");



    $custom = getCustomer($rfid);


    $ids = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'search',
        array(array(array('order_id', '=', $orderid)
            ))
        );
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'read', array($ids));
    $array = array();






    foreach ($records as $record){

        array_push($array,new Orderline($record["id"], $record["product_id"][1], $record["qty"], $record["price_unit"],$record["order_id"],$custom->uuid));
    }                                      //$uuid,$product,$aantal,$prijs,$orderid,$userUuid

    return $array;

}

/*
function getPartnerRef($partnerid)
{
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $ides = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('id', '=', $partnerid)
            )),
        array('limit'=>1));

    $recordes = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ides));

    foreach ($recordes as $r)
    {
        $la = $r["ref"];
    }



    return $la;

}*/

