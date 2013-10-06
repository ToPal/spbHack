<?php

include_once("main_funcs.php");

// available functions names
define("getRaitingByAddress", "getRaitingByAddress");

$res = array();

try {
    if (!isset($_POST["func"])) {
        $func = getRaitingByAddress;
    } else {
        $func = $_POST["func"];
    }

    switch ($func) {
        case getRaitingByAddress: $res = func_getRaitingByAddress(); break;

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

echo json_encode($res);