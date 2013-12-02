<?php

include_once('funcs.php');
include_once('Ratings.php');
include_once('qrs.php');
include_once('Nearest.php');

function func_getRatingByAddress($address = null) {
    if (($address == null) && !isset($_POST["address"])) {
        throw new Exception("Incorrect address");
    }

    if ($address == null) {
        $address = $_POST["address"];
    }
    $coords = getCoordsByAddress($address);
    if (!$coords) {
        throw new Exception("Can't calculate coordinates for this address");
    }

    $res = getCumulativeRating($coords);
    $res['coords'] = $coords;
    $res['nearest'] = getNearest();
    $res['map'] = "http://".$_SERVER['SERVER_NAME']."/map.php?x=".$coords['longitude']."&y=".$coords['latitude'];

    return $res;
}

function func_generateImg() {
    if (!isset($_GET['address'])) {
        throw new Exception("Incorrect address");
    }

    $image = imagecreatetruecolor(140, 18);
    $fon = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $fon);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $rating = func_getRatingByAddress($_GET['address']);
    imagestring($image, 4, 0, 0, round($rating['raiting']), $text_color);

    header('Content-type: image/png');
    imagepng($image);
}

function func_getPoints() {
    $res = array();
    $res['points'] = getPointsForMap();
    return $res;
}

function func_getPointArrays() {
    $res = array();
    define("FuncName", "PointArrays");
    $pointArrays = array();

    $points = getPointsForMap();
    $pointArrays['count'] = count($points);
    if ($pointArrays['count'] == 0) {
        $res[FuncName] = $pointArrays;
        return $res;
    }

    foreach ($points as $point) {
        foreach ($point as $k => $v) {
            $res[$k][] = $v;
        }
    }

    $res[FuncName] = $pointArrays;
    return $res;
}