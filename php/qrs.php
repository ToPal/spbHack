<?php
    include_once('lib_DB_init.php');


    define("TABLE_Coords", "Coords");

    define("Coords_ID", "ID");
    define("Coords_address", "Address");
    define("Coords_latitude", "Latitude");
    define("Coords_longitude", "Longitude");

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
define("Points_NumLatitude", "Num_Latitude");
define("Points_NumLongitude", "Num_Longitude");
define("Points_Latitude", "Latitude");
define("Points_Longitude", "Longitude");

function addPoint($num_x, $num_y, $x, $y) {
    if (! (is_numeric($x) && is_numeric($y) && is_numeric($num_x) && is_numeric($num_y)) ) {
        return false;
    }

    $props[Points_NumLatitude] = $num_x;
    $props[Points_NumLongitude] = $num_y;
    $props[Points_Latitude] = $x;
    $props[Points_Longitude] = $y;

    return itdb(TABLE_Points, $props);
}

function getPointId($num_x, $num_y) {
    if (! (is_numeric($num_x) && is_numeric($num_y)) ) {
        return false;
    }

    $q = "SELECT ".Points_ID." FROM ".TABLE_Points." WHERE ".Points_NumLatitude."=".$num_x." AND ".Points_NumLongitude."=".$num_y.";";
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


function getPointsForMap() {
    $q = "SELECT ".Points_Latitude.", ".Points_Longitude.", SUM(".Results_Result.")".
        " FROM ".TABLE_Results.", ".TABLE_Points.
        " WHERE ".TABLE_Points.".".Points_ID."=".TABLE_Results.".".Results_PointId.
        " GROUP BY ".TABLE_Points.".".Points_ID;

    return gafdb($q);
}





define("TABLE_Files", "Files");

define("Files_ID", "ID");
define("Files_Name", "Name");
define("Files_Url", "Url");
define("Files_Filename", "Filename");
define("Files_LastUpdate", "Last_update");

function getFilesInformationFromDB() {
    $q = "SELECT * FROM ".TABLE_Files;

    return gafdb($q);
}

function addFileInformationToDB($name, $url, $filename, $lastUpdate) {
    $props[Files_Name] = $name;
    $props[Files_Url] = $url;
    $props[Files_Filename] = $filename;
    $props[Files_LastUpdate] = $lastUpdate;

    return itdb(TABLE_Files, $props);
}

function delFileInformationFromDB($id) {
    $q = "DELETE FROM ".TABLE_Files." WHERE ".Files_ID."=".$id;

    return gefdb($q);
}

function updFileInformationFromDB($id, $name, $url, $filename, $lastUpdate) {
    $q = "UPDATE ".TABLE_Files." SET ".Files_Name."=".$name.", ".Files_Url."=".$url.", "
        .Files_Filename."=".$filename.", ".Files_LastUpdate."=".$lastUpdate
        ." WHERE ".Files_ID."=".$id;

    return gefdb($q);
}





define("TABLE_Data", "Data");

define("Data_ID", "ID");
define("Data_DatabaseID", "DatabaseID");
define("Data_Latitude", "Latitude");
define("Data_Longitude", "Longitude");
define("Data_String", "String");


function addDataRowToDB($DatabaseID, $latitude, $longitude, $string) {
    if (! (is_numeric($DatabaseID) && is_numeric($latitude) && is_numeric($longitude) && ($string != "")) ) {
        return false;
    }

    $props[Data_DatabaseID] = $DatabaseID;
    $props[Data_Latitude] = $latitude;
    $props[Data_Longitude] = $longitude;
    $props[Data_String] = $string;

    return itdb(TABLE_Data, $props);
}