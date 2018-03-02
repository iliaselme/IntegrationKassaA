<?php

/**
 * Created by PhpStorm.
 * User: sennevervaecke
 * Date: 5/10/2017
 * Time: 2:01 PM
 */
class LocalError
{
    public $MessageFrom;
    public $function;
    public $error;

    function __construct($function, $error, $MessageFrom)
    {
        $this->MessageFrom = $MessageFrom;
        $this->function = $function;
        $this->error = $error;
    }
}
/**
 * id: 1    message: failed to get id.
 * id: 2    message: customer already exists.
 * id: 3    message: customer doesn't exist.
 * id: 4    message: payedAmount < 0 reservation
 * id: 5    message: not enough money
 */