<?php

function getUuid($randString){
    try {

        $json = file_get_contents('http://10.3.51.30:666/uuid/'.$randString."f");

        $data = json_decode($json);

        return $data->uuid;
    } catch (Exception $e){
        return "fail";
        
    }
}

function getVersion(){
    try {
        $json = file_get_contents('http://10.3.51.30:666/version');

        $data = json_decode($json);

        return $data->version;
    } catch (Exception $e){
        return "fail";

    }


}
?>