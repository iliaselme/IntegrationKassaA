<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Orderline.php";
getAllOrderLines();


function getAllOrderLines()
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
        array(array(array( "order_id", "=" ,19),
        ))
    );
    $arrayorder = array();
    foreach ($ids as $id)
    {
        $records = $models->execute_kw($db, $uid, $password,
            'pos.order.line', 'read', array($id),array('fields'=>array('name', 'create_date', 'product_id')));
        //var_dump($records);
        $array = array("id" => "company_id","pid" => "product_id");
        //var_dump($array);
        $array = array();
       foreach ($records as $record){
     // return new Orderline($record["name"], $record["id"],  $record["id"]);
          // var_dump($record);
    array_push( $array, array("id" => $record[0], "naam" => $record[1]));

           var_dump($array[0]['id']);
        }


    }

    return $array;
/*
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'read', array($ids));

  //  function _construct($id,$product_id/*$price,$aantal/*, $order_id)

    foreach ($records as $record){
        array_push($arrayorder, new Orderline($record["company_id"], $record["product_id"], /*$record["price_unit"], $record["price_unit"], $record["name"],$record["order_id"]));
    }
*/
   // return $arrayorder;

   /* $ids = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'search',
        array(array(array( 1, "=" ,1),
        ))
    );
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'read', array($ids));

   // function _construct($id,$product_id,$price,$aantal, $order_id)
    $arrayorder = array();
    foreach ($records as $record){
        array_push($arrayorder, new Orderline($record["id"], $record["product_id"], $record["price_unit"], $record["qty"], $record["order_id"]));
    }

    return $arrayorder;*/
}

