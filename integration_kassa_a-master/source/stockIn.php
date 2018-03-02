<?php
/**
 * Created by PhpStorm.
 * User: ilias
 * Date: 29-05-17
 * Time: 11:32
 */

require_once('ripcord-master/ripcord.php');
require_once "Orderline.php";
require_once 'order.php';

stock();

function stock()
{


    $url = "http://10.3.51.26:8069";

    $db = "odoo";
    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $p= $models->execute_kw($db, $uid, $password, 'stock.inventory', 'read',
        array(array('fields'=>array('product_name', 'product_qty'))));

    var_dump($p);

}