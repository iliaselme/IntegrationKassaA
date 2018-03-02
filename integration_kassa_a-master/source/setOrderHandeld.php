<?php
/**
 * Created by PhpStorm.
 * User: ilias
 * Date: 23-05-17
 * Time: 13:27
 */
require_once('ripcord-master/ripcord.php');
require_once "Orderline.php";
require_once 'order.php';

setOrderHandled(19);

function setOrderHandled($Order_Id)
{


    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $models->execute_kw($db, $uid, $password, 'pos.order', 'write',
        array($Order_Id, array('x_handled'=>true)));

    $result = $models->execute_kw($db, $uid, $password,
        'pos.order', 'read',
        array($Order_Id)
    );


}