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

$a=getOrderNew();
var_dump($a);
function controleOrderCustomer($customerId)
{


    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $order_ids = $models->execute_kw(
        $db,
        $uid,
        $password,
        'pos.order',
        'search',
        array(
            array(
                array( "partner_id" , '=', $customerId)
            )
        )
    );



    $orders = $models->execute_kw($db, $uid, $password,
        'pos.order', 'read', array($order_ids),
        array('fields'=>array('partner_id', 'lines','amount_paid','amount_total','create_date')));


    $lines = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'read', array($orders[0]["lines"]),
        array('fields' => array('display_name', 'product_id', 'qty', 'price_subtotal_incl')));

    $a = new Orderline($lines[0]["display_name"],$lines[0]["product_id"][0],$lines[0]["qty"]);



    $arrayOrders=array();
    foreach ($orders as $order) {
        $lines = $models->execute_kw($db, $uid, $password,
            'pos.order.line', 'read', array($order["lines"]),
            array('fields' => array('display_name', 'product_id', 'qty', 'price_subtotal_incl')));


        $arrayOrderLines=array();
        array_push($arrayOrderLines,new Orderline($lines[0]["display_name"],$lines[0]["product_id"][0],$lines[0]["qty"]));
        $oo = new Order($order['id'],$order['partner_id'],$order['create_date'],7,$order['amount_paid'],$arrayOrderLines);
        array_push($arrayOrders,$oo);

        $partner_id = $models->execute_kw(
            $db,
            $uid,
            $password,
            'res.partner',
            'search',
            array(
                array(
                    array('id', '=', $order['partner_id'][0])
                )
            )
        );
        // var_dump($order);

    }
    return $arrayOrders;


}