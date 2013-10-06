<?php
    include_once('lib_DB_init.php');


    define("TABLE_Coords", "Coords");

    define("Coords_ID", "ID");
    define("Coords_address", "Address");
    define("Coords_latitude", "X");
    define("Coords_longitude", "Y");

function getCoordsFromDB($address) {
    $q = "SELECT ".Coords_latitude.", ".Coords_longitude." FROM ".TABLE_Coords." WHERE ".Coords_address."='".$address."'";

    $res = grfdb($q);
    if (!$res) {
        return FALSE;
    }

    $coords = array();
    $coords['latitude'] = $res[Coords_latitude];
    $coords['longitude'] = $res[Coords_longitude];
    return $coords;
}

function addCoordsToDB($address, $latitude, $longitude) {
    if (! (is_numeric($latitude) && is_numeric($longitude)) ) {
        return false;
    }

    $props[Coords_address] = $address;
    $props[Coords_latitude] = $latitude;
    $props[Coords_longitude] = $longitude;

    return itdb(TABLE_Coords, $props);
}



define("TABLE_Points", "Points");

define("Points_ID", "ID");
define("Points_NumX", "Num_x");
define("Points_NumY", "Num_y");
define("Points_X", "X");
define("Points_Y", "Y");

function addPoint($num_x, $num_y, $x, $y) {
    if (! (is_numeric($x) && is_numeric($y) && is_numeric($num_x) && is_numeric($num_y)) ) {
        return false;
    }

    $props[Points_NumX] = $num_x;
    $props[Points_NumY] = $num_y;
    $props[Points_X] = $x;
    $props[Points_Y] = $y;

    return itdb(TABLE_Points, $props);
}

function getPointId($num_x, $num_y) {
    if (! (is_numeric($num_x) && is_numeric($num_y)) ) {
        return false;
    }

    $q = "SELECT ".Points_ID." FROM ".TABLE_Points." WHERE ".Points_NumX."=".$num_x." AND ".Points_NumY."=".$num_y.";";
    return gefdb($q);
}



define("TABLE_Results", "Results");

define("Results_ID", "ID");
define("Results_PointId", "ID_Point");
define("Results_DatasetId", "ID_Dataset");
define("Results_Result", "Result");

function addDataResult($datasetId, $pointId, $result) {
    if (! (is_numeric($pointId) && is_numeric($datasetId) && is_numeric($result))) {
        return false;
    }

    $temp_result = getDataIdAndResult($datasetId, $pointId);
    if ( (!$temp_result) || ($temp_result == "") ) {
        $props[Results_DatasetId] = $datasetId;
        $props[Results_PointId] = $pointId;
        $props[Results_Result] = $result;

        return itdb(TABLE_Results, $props);
    } elseif ($temp_result[Results_Result] > $result) {
        return updateDataResult($temp_result[Results_ID],  $temp_result);
    }
    return 1;
}

function getDataResult($datasetId, $pointId) {
    if (! (is_numeric($pointId) && is_numeric($datasetId))) {
        return false;
    }

    $q = "SELECT ".Results_Result." FROM ".TABLE_Results." WHERE ".
        Results_DatasetId."=".$datasetId." AND ".Results_PointId."=".$pointId.";";

    return gefdb($q);
}

function getDataIdAndResult($datasetId, $pointId) {
    if (! (is_numeric($pointId) && is_numeric($datasetId))) {
        return false;
    }

    $q = "SELECT ".Results_ID.", ".Results_Result." FROM ".TABLE_Results." WHERE ".
        Results_DatasetId."=".$datasetId." AND ".Results_PointId."=".$pointId.";";

    return grfdb($q);
}

function updateDataResult($id, $result) {
    if (! (is_numeric($id) && is_numeric($result))) {
        return false;
    }

    $q = "UPDATE ".TABLE_Results." SET ".Results_Result."=".$result." WHERE ".Results_ID."=".$id.";";
    return gefdb($q);
}