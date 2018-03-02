<?php
require_once "GetFromURL.php";
class Order {

    public $id;
    public $name;
    public $create_date;
    public $amount_paid;
    public $orderlines;


    public $uuid;



function __construct($id,$naam , $date, $amountPaid , $o, $uuid)
{


$this->name = $naam;
$this->create_date = $date;

$this->amount_paid = $amountPaid;
$this->id=$id;
$this->orderlines=$o;
$this->uuid = $uuid;


    }

}

?>