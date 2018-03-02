<?php

Class Reservation{

    public $name;
    public $x_last_name;
    public $create_date;
    public $city;
    public $email;
    public $mobile;
    public $ref; // dit gaan we gebruiken als universele id (string)
    public $breakfast;
    public $extra;
    public $payedAmount;


    function __construct($name,$x_last_name, $create_date, $city, $email, $mobile,$ref,$breakfast,$extra,$payedAmount)
    {
        $this->name = $name;
        $this->x_last_name = $x_last_name;
        $this->create_date = $create_date;
        $this->city = $city;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->ref = $ref;
        $this->breakfast = $breakfast;
        $this->extra = $extra;
        $this->payedAmount = $payedAmount;
    }



}