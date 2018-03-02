<?php

/**
 * Created by PhpStorm.
 * User: ilias
 * Date: 10-05-17
 * Time: 15:00
 */
class Orderline
{
    public $uuid;
    public $product;
    public $aantal;
    public $price;

    public $userUuid;

    function __construct($uuid,$product,$aantal,$prijs,$orderid,$userUuid)
    {
        $this->aantal = $aantal;
        $this->uuid = $uuid;
        $this->product = $product;
        $this->price = $prijs;
        $this->userUuid = $userUuid;

    }
}