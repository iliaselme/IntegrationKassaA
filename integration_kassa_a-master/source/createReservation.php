<?php
require_once "sendLog.php";
require_once "Reservation.php";

function createReservation($reservation){
    if($reservation->payedAmount<0){
        return 'Reservation: payed amount is less than 0.';
        //sendError(new LocalError(4, 'Reservation: payed amount is less than 0.', json_encode($reservation)));
    }
    if($reservation->breakfast==true)
        $heeftBreakfast = 1;
    else
        $heeftBreakfast=0;
    require_once('ripcord-master/ripcord.php');
    $url = "http://10.3.51.26:8069";
    $db = "odoo";
    $username = "dRRty@hotmail.be";
    $password = "test123";
    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");
    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('email', '=', $reservation->email),
            array('customer', '=', true))),
        array('limit'=>1));
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));
// count the number of fields fetched by default

    if(count($records)==0){
        $arrayCust = array('previous_id' => "0");
        $json = json_encode($arrayCust);
        $id = $models->execute_kw($db, $uid, $password,
            'res.partner', 'create',
            array(array('name'=>$reservation->name,'x_last_name'=>$reservation->x_last_name,'create_date'=> $reservation->create_date,'city'=>$reservation->city,'email'=>$reservation->email,'mobile'=>$reservation->mobile,'ref'=>$reservation->ref,'comment'=>$json,'x_reservation' => 1,'x_breakfast'=> true,'x_payedamount' => true,'x_extra'=> $reservation->extra, 'x_create' => true)));
        if(isset($id)){
            sendLogReservation($reservation,"monitoring");

        }
    }else{
        //sendError(new LocalError("createReservation", 'Reservation already exists.', "unknown"));
        echo "Reservation bestaat al";
        return 'Reservation already exists.';
    }
}
?>