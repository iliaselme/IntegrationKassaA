<?php
require_once "sendLog.php";
require_once "Customer.php";
require_once "order.php";


while(true){
    $results = [];
    $results = getToCreate();
    if(!empty($results)){
        $count = 1;
        foreach ($results as $result){
            echo $count.": ".$result->name."\n";
            $count++;
            sendLogCustomerCreate($result, "monitoring");
            sendLogCustomerCreate($result, "CRM");
            sendLogCustomerCreate($result, "frontend");
            sendLogCustomerCreate($result, "cloud");
        }
        setCreateTrue();
    }

    $results = getToBlock();
    if(!empty($results)){
        $count = 1;
        foreach ($results as $result){
            echo $count.": ".$result->name."\n";

            sendLogCustomerBlock($result, "monitoring");
            sendLogCustomerBlock($result, "CRM");
            sendLogCustomerBlock($result, "frontend");
            sendLogCustomerBlock($result, "cloud");

            $count++;

        }
        setUpdateBlockedFalse();
    }

    $results = getToUpdate();
    if(!empty($results)){
        $count = 1;
        foreach ($results as $result){
            echo $count.": ".$result->name."\n";

            sendLogCustomerUpdate($result, "monitoring");
            sendLogCustomerUpdate($result, "CRM");
            sendLogCustomerUpdate($result, "frontend");
            sendLogCustomerUpdate($result, "cloud");
            //sendLogCustomerUpdate($result, )

            $count++;

        }
        setUpdateFalse();
    }
    /*
    $results = getToBlock();
    if(!empty($results)){
        $count = 1;
        foreach ($results as $result){
            echo $count.": ".$result->name."\n";
            $count++;
            sendLogCustomerBlock($result, "monitoring");
            sendLogCustomerBlock($result, "CRM");

        }
        setStuurBlockedFalse();
    }

    $results = getToUnblock();
    if(!empty($results)){
        $count = 1;
        foreach ($results as $result){
            echo $count.": ".$result->name."\n";
            $count++;
            sendLogCustomerUnblock($result, "monitoring");
            sendLogCustomerUnblock($result, "CRM");

        }
        setStuurUnblockedFalse();
    }
    */


}

//}

function getUnhandledOrders($customerId){
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");


    $ids = $models->execute_kw($db, $uid, $password,
        'pos.order', 'search',
        array(array(array('x_handled', '=', false), array('partner_id', '=', $customerId)))
    );
    foreach ($ids as $id){
        echo $id;
    }
    $orders = [];
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order', 'read', array($ids));

    foreach ($records as $record) {
        array_push($orders, new Order($record["id"],
            getUuid($record["id"].date_format($record["create_date"],"YYYYmmddHHiiss")),
            $record["name"],
            $record["create_date"],
            $record["amount_paid"]));
    }
    return $orders;
}


function getToCreate()
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
        array(array(array('x_create', '=', false)))


    );
    $customers = [];
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));
    foreach ($records as $record) {
        array_push($customers, new Customer(null,$record["name"], $record["x_last_name"], $record["create_date"], $record["city"], $record["email"], $record["mobile"], $record["ref"], $record["x_rfid"], $record["x_money"]));
    }
    return $customers;
}
function getToBlock()
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
        array(array(array('x_update', '=', true),array('x_blocked','=',true)))


    );
    $customers = [];
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));
    foreach ($records as $record) {
        array_push($customers, new Customer(null,$record["name"], $record["x_last_name"], $record["create_date"], $record["city"], $record["email"], $record["mobile"], $record["ref"], $record["x_rfid"], $record["x_money"]));
    }
    return $customers;
}




function getToUpdate()
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
            array(array(array('x_update', '=', true)))

        );
        $customers = [];
        $records = $models->execute_kw($db, $uid, $password,
            'res.partner', 'read', array($ids));
        foreach ($records as $record) {
            array_push($customers, new Customer(null,$record["name"], $record["x_last_name"], $record["create_date"], $record["city"], $record["email"], $record["mobile"], $record["ref"],$record["x_rfid"],$record["x_money"]));
        }
        return $customers;
    }


function getToUnblock()
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
        array(array(array('x_blocked', '=', false),array('x_stuurUnblocked','=',true)))

    );
    $customers = [];
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));
    foreach ($records as $record) {
        array_push($customers, new Customer(null,$record["name"], $record["x_last_name"], $record["create_date"], $record["city"], $record["email"], $record["mobile"], $record["ref"],$record["x_rfid"],$record["x_money"]));
    }
    return $customers;
}



    function setCreateTrue(){
        echo "aangeroepen";
        $url = "http://10.3.51.26:8069";

        $db = "odoo";

        $username = "dRRty@hotmail.be";

        $password = "test123";

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());
        $models = ripcord::client("$url/xmlrpc/2/object");

        $ids = $models->execute_kw($db, $uid, $password,
            'res.partner', 'search',
            array(array(array('x_create', '=', false)
                )));
        /*$records = $models->execute_kw($db, $uid, $password,
            'res.partner', 'read', array($ids));*/

        foreach($ids as $id){
            echo $id;
            $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
                array(array($id), array('x_create'=>true), array('ref'=>getUuid(date("YmdHis") . $id))));
        }

        }
function setUpdateFalse(){
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('x_update', '=', true)
        )));
    /*$records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));*/

    foreach($ids as $id){

        $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
            array(array($id), array('x_update'=>false)));
    }

}

function setUpdateBlockedFalse(){
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('x_update', '=', true), array('x_blocked', '=', true)
        )));
    /*$records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));*/

    foreach($ids as $id){

        $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
            array(array($id), array('x_update'=>false)));
    }

}


function setStuurUnblockedFalse(){
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('x_stuurUnblocked', '=', true)
        )));
    /*$records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));*/

    foreach($ids as $id){

        $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
            array(array($id), array('x_stuurUnblocked'=>false)));
    }

}



?>
