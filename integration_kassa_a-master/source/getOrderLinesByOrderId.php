<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Orderline.php";
$id=1;
getOrderLinesByOrderId($id);
function getOrderLinesByOrderId($id)
{
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $ids = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'search',
        array(array(array( "order_id", "=" ,$id), array('limit' => 1)
        ))
    );
    $arrayorder = array();
    foreach ($ids as $id)
    {
        $records = $models->execute_kw($db, $uid, $password,
            'pos.order.line', 'read', array($id));
        var_dump($records);
        $array = array("id" => "company_id","pid" => "product_id");
      //  array_push($arrayorder, new Orderline($records["company_id"], $records["product_id"], /*$record["price_unit"], $record["price_unit"],*/ $records["name"]/*,$record["order_id"]*/));

    }


    return $arrayorder;


}

