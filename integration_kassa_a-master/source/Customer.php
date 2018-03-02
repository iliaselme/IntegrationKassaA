<?php

Class Customer{

    public $id;
    public $name;
    public $x_last_name;
    public $create_date;
    public $city;
    public $email;
    public $mobile;
    public $uuid; // dit gaan we gebruiken als universele id (string)
    public $rfid;
    public $credit;

    function __construct($id, $name,$x_last_name, $create_date, $city, $email, $mobile,$uuid, $rfid, $credit)
    {
        $this->id = $id;
        $this->name = $name;
        $this->x_last_name = $x_last_name;
        $this->create_date = $create_date;
        $this->city = $city;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->uuid = $uuid;
        $this->rfid = $rfid;
        $this->credit = $credit;
    }



}