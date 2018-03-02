<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Product.php";

function getProduct($product_id)
{
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $ids = $models->execute_kw($db, $uid, $password,
        'product.attribute', 'search',
        array(array(array('product_id', '=', $product_id)
           )),
        array('limit' => 1));
    $records = $models->execute_kw($db, $uid, $password,
        'product.attribute', 'read', array($ids));
    foreach ($records as $record){
        return new Product($record["name"]);
    }

}

