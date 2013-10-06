<?php

include_once("main_funcs.php");

// available functions names
define("getRaitingByAddress", "getRaitingByAddress");
define("getRaitingImage", "getRaitingImage");
define("getPoints", "getPoints");

$res = array();

try {
    if (!isset($_POST["func"])) {
        if (isset($_GET['address'])) {
            $func = getRaitingImage;
        } else {
            $func = getRaitingByAddress;
        }
    } else {
        $func = $_POST["func"];
    }

    switch ($func) {
        case getRaitingByAddress: $res = func_getRaitingByAddress(); break;
        case getRaitingImage: func_generateImg(); break;
        case getPoints: $res = func_getPoints(); break;

        default: throw new Exception("Неизвестная функция");
    }

} catch (Exception $e) {
    $res['errorMessage'] = $e->getMessage();
}

if (isset($res['errorMessage'])) {
    $res['result'] = 'fail';
} else {
    $res['result'] = 'success';
}

if (($res['result'] == 'fail') || ($func != getRaitingImage)) {
    echo json_encode($res);
}