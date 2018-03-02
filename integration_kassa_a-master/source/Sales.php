<?php

require_once('ripcord-master/ripcord.php');

$url = "http://10.3.51.26:8069";

$db = "odoo";

$username = "dRRty@hotmail.be";

$password = "test123";



$common = ripcord::client("$url/xmlrpc/2/common");
$uid = $common->authenticate($db, $username, $password, array());
$models = ripcord::client("$url/xmlrpc/2/object");

	$ids = $models->execute_kw($db, $uid, $password,
    'pos.order', 'search',
    array(array(array('amount_paid', '>=', 0))));
	
	$records = $models->execute_kw($db, $uid, $password,
    'pos.order', 'read', array($ids));

	
	foreach($records as $record){
	echo "Amount paid: " . $record['amount_paid'] . "Amount return: " . $record['amount_return'] . " - tax: " . $record['amount_tax'];
    echo " - total: " . $record['amount_total'] . " - Create date: " . $record['create_date'];
		echo '<br>';
	}

//date moet nog geconverteerd worden naar dd/mm/yyyy HH:mm:ss:mili

?>
