<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "order.php";

var_dump(getOrder(12));
function getOrder($ref)
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
        array(array(array('id', '=', $ref),
            array('active', '=', true))),
        array('limit' => 1));
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order', 'read', array($ids));
    var_dump($records);
    foreach ($records as $record){
        return new Order($record["id"], $record["name"], $record["create_date"], $record["ids"], $record["amount_paid"]);
    }

}

