<?php
require_once('ripcord-master/ripcord.php');
require_once "getCustomer.php";
require_once "getMoney.php";
require_once "sendLog.php";
session_start();
?>
<form method="post" action="PortalMoney.php">
    <input type="text" name="rfid" placeholder="rfid"/>
    <input type="submit" value="send" />
</form>


<?php
if(isset($_POST['addMoney'])){

    $money = $_POST['geld'];
    if($money>0){
        $rfid = $_SESSION['rfid'];
        $vorigeMoney= getMoney($rfid);

        $money += $vorigeMoney;
        $id = $_SESSION['id'];
        $models->execute_kw($db, $uid, $password, 'res.partner', 'write',
        array(array($id), array('x_money'=>$money)));

        sendLogAccountMoney($money,$rfid,"monitoring");


    }



}


if(isset($_POST["rfid"])) {

    $rfid = $_POST["rfid"];
    $_SESSION['rfid'] = $rfid;
    $url = "http://10.3.51.26:8069";

    $db = "odoo";

    $username = "dRRty@hotmail.be";

    $password = "test123";

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    $models = ripcord::client("$url/xmlrpc/2/object");

    $customer = getCustomer($rfid);
    if(empty($customer)){
        echo $rfid . " rfid doesnt exist or is blocked/inactive";
    }else{
        $id = $customer->id;
        $_SESSION['id'] = $id;

        if (isset($customer)) {
            echo "voornaam:" . $customer->name;
            echo "<br> saldo:" . $customer->credit;
            ?>
            <form method="post" action="PortalMoney.php">
                <input type="number" name="geld"/>
                <input type="submit" value="add" name="addMoney"/>

            </form>

            <?php
        } else {
            echo 'error!!!!';
        }};
}

?>
