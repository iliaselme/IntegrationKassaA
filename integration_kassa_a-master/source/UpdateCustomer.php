<?php

require_once "sendLog.php";
require_once "Customer.php";
require_once('ripcord-master/ripcord.php');
require_once "setInactiveCustomer.php";
require_once "getCustomer.php";

function updateCustomer($ref,$customer){
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    //$vorigeVersion = setInactive($ref);
    //$actualVersion = $vorigeVersion++;

    $results = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('ref', '=', $customer->uuid),
            array('active', '=', true))),
        array('limit'=>1));

    /*
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($customer->id));
*/
// count the number of fields fetched by default
    // NU KAN EEN GEBLOKKEERDE GEBRUIKER EEN NIEUWE ACCOUNT MAKEN KAN DAT ? ALS DAT NIET KAN DAN MOET CUSTOMER = TRUE WEG GEWOON

    // VOOR DE UNIVERSELE ID KAN DAT IN DE VELD ref (string) van res.partner
    if(!isset($results["faultCode"])) {
        foreach ($results as $result) {

            $localCustomer = getCustomerByUuid($ref);
            $rfid = $localCustomer->rfid;

            echo is_numeric($result) . " " . $result;
            $ids = $models->execute_kw($db, $uid, $password,
                'res.partner', 'search',
                array(array(array('ref', '=', $customer->uuid),
                    array('customer', '=', true))),
                array('limit' => 1));
            $records = $models->execute_kw($db, $uid, $password,
                'res.partner', 'read', array($ids));

            $id = $models->execute_kw($db, $uid, $password,
                'res.partner', 'create',
                array(array('name' => $customer->name, 'x_last_name' => $customer->x_last_name, 'create_date' => $customer->create_date, 'city' => $customer->city, 'email' => $customer->email, 'mobile' => $customer->mobile, 'ref' => $customer->uuid, 'comment' => $customer->id, 'x_rfid' => $rfid, 'x_money' => $localCustomer->credit, 'x_create' => true)));


            if (isset($id)) {
                setInactive($customer->id);
                sendLogCustomerUpdate($customer, "monitoring");
                //sendLogCustomerUpdate($customer, "CRM");

            }

        }
    }
    else {
        return "customer doesn't exist";
    }

}


?>
