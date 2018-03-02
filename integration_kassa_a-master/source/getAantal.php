<?php
require_once('ripcord-master/ripcord.php');
function getAantalCustomers()
{
    $url = "http://10.3.51.26:8069";
    $db = "odoo";
    $username = "dRRty@hotmail.be";
    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

   $aantal = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search_count',
        array(array(array('x_reservation', '=', "0"),
            array('customer', '=', true))));

    return $aantal;
}


function getAantalReservation()
{
    $url = "http://10.3.51.26:8069";
    $db = "odoo";
    $username = "dRRty@hotmail.be";
    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $aantal = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search_count',
        array(array(array('x_reservation', '=', "1"),
            array('customer', '=', true))));

    return $aantal;
}



?>