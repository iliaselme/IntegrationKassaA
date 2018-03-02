<?php
require_once('ripcord-master/ripcord.php');
require_once "sendLog.php";
require_once "Customer.php";
require_once "order.php";
require_once "UpdateCustomer.php";
require_once "Orderline.php";
require_once "GetFromURL.php";

if(!empty($_POST["rfid"])){
    $rfid = $_POST["rfid"];
    $customer = getCustomer($rfid);
    if(count($customer) == 0){
        echo "<script type='text/javascript'>alert('" . $rfid . " is niet gelinkt aan een customer');</script>";
        unset($_POST["rfid"]);
    }
    else {
        $orders = getUnhandledOrders($customer->id);

        $sum = 0;
        foreach ($orders as $order) {
            $sum += $order->amount_paid;
        }
        if (count($orders) == 0) {
            echo "<script type='text/javascript'>alert('" . $customer->name . " heeft geen orders!');</script>";
            unset($_POST["rfid"]);
        } else if ($customer->credit < $sum) {
            echo "<script type='text/javascript'>alert('" . $customer->name . "not enough credit!');</script>";

            sendError(new LocalError(5, "not enough money.", "kassa:RfidController.php Confirmation of purchases"));
            unset($_POST["rfid"]);
        } else if(empty($_POST["confirm"])) {
            ?>
            <p><?php echo $customer->name; ?> betaalt <?php echo $sum; ?> euro.</p>
            <form method="post">
                <input type="hidden" name="rfid" value="<?php echo $rfid; ?>">
                <input type="checkbox" name="confirm" value="1"> correct <br>
                <button type="submit">confirm</button>
            </form>
            <?php
        }
        if (isset($_POST["confirm"]) && !empty($_POST["rfid"])) {
            $confirm = $_POST["confirm"];
            if ($confirm = "1") {
                $customer->credit = $customer->credit - $sum;
                updateCustomer($customer->uuid, $customer);
                foreach ($orders as $order) {
                    setOrderHandled($order->id);
                    $que = "monitoring";
                    $que2 = "kassa";
                 //   sendLogOrderCreate($order,$que);
                    $rfid = $_POST["rfid"];
                   sendLogOrderLineCreate($order,$que,$rfid);

                }
            }
            unset($_POST["confirm"]);
            unset($_POST["rfid"]);
        }
    }
}

?>
<?php if(empty($_POST["rfid"])) { ?>

    <form method="post">
        <label>RFID: </label><input type="text" name="rfid">
        <button type="submit">betalen</button>
    </form>
<?php } ?>

<?php

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
/*
function getCustomer($rfid)
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
        array(array(array('x_rfid', '=', $rfid),
            array('active', '=', true))),
        array('limit' => 1));
    $records = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));
    foreach ($records as $record){
        return new Customer($record["id"], $record["name"], $record["x_last_name"], $record["create_date"], $record["city"], $record["email"], $record["mobile"],$record["ref"], $rfid, $record["x_money"]);
    }

}*/


function getOrderLinesByOrderId($order_id)
{
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $arrayLines= array();

    $ids = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'search',
        array(array(array("order_id", '=' ,$order_id)))
    );
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order.line', 'read', array($ids));
    foreach ($records as $record)
        //($id,$product_id,$aantal
    {
        array_push($arrayLines,new Orderline($record["id"],$record["product_id"][1],$record["qty"]));


    }

    return $arrayLines;
}



function getUnhandledOrders($customerId){
    $url = "http://10.3.51.26:8069";22;

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
    $orders = array();
    $records = $models->execute_kw($db, $uid, $password,
        'pos.order', 'read', array($ids));


    foreach ($records as $list) {

        $uuid = getUuid($list["id"]);

        //($id,$naam , $date, $amountPaid , $o, $uuid)
        array_push($orders,
            new Order($list["id"],$uuid,$list["partner_id"][0],$list["amount_total"],$list["date_order"],$list["lines"],null));

        $arrayLines = array();

    }
    return $orders;
}