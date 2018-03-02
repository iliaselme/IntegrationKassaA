<?php

/**
 * Created by PhpStorm.
 * User: sennevervaecke
 * Date: 5/30/2017
 * Time: 2:34 PM
 */
class LocalAck
{
    public $MessageFrom;
    public $function;
    public $info;

    function __construct($function, $info, $MessageFrom)
    {
        $this->function = $function;
        $this->info = $info;
        $this->MessageFrom = $MessageFrom;
    }
}