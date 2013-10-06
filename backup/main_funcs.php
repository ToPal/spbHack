<?php

include_once('funcs.php');
include_once('Raitings.php');
include_once('qrs.php');

function func_getRaitingByAddress() {
    if (!isset($_POST["address"])) {
        throw new Exception("Incorrect address");
    }

    $address = $_POST["address"];
    $coords = getCoordsByAddress($address);
    if (!$coords) {
        throw new Exception("Can't calculate coordinates for this address");
    }

    $res = getCumulativeRaiting($coords);
    $res['map'] = "http://".$_SERVER['SERVER_NAME']."/map.php?x=".$coords['longitude']."&y=".$coords['latitude'];

    return $res;
}

function func_getLocalRaitings() {
    $res['LocalRaitings'] = getAllLocalRaitings();

    return $res;
}