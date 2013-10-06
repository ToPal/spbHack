<?php

    include_once('qrs.php');
    include_once('funcs.php');
    include_once('points_declarations.php');



$p['latitude'] = 55.75803;
$p['longitude'] = 37.572437;
$x = getRanges($p, 0.5, 3);

//Создаёт сетку для Москвы
function create_points() {
    for ($i_x = 0; $i_x < count_x; $i_x++) {
        for ($i_y = 0; $i_y < count_y; $i_y++) {
            $point = getPointByNum($i_x, $i_y);

            addPoint($i_x, $i_y, $point['longitude'], $point['latitude']);
        }
    }
}


function getPointByNum($num_x, $num_y) {
    $res = array();
    $res['longitude'] = start_x - $num_x * delta_x;
    $res['latitude'] = $num_y * delta_y + start_y;

    return $res;
}

function getPrevPointNums($point) {
    $x = $point['longitude'];
    $y = $point['latitude'];

    if (! (is_numeric($x) && is_numeric($y)) ||
        ($x > start_x) || ($x < end_x) ||
        ($y < start_y) || ($y > end_y)) {
        return false;
    }

    $res = array();
    $res['num_x'] = floor((start_x - $x) / delta_x);
    $res['num_y'] = floor(($y - start_y) / delta_y);

    return $res;
}

function getNearestPoint($point) {
    $num = getPrevPointNums($point);
    $min_distance = getDistance($point, getPointByNum($num['num_x'], $num['num_y']));
    $res = $num;

    $temp_point = getPointByNum($num['num_x'] + 1, $num['num_y']);
    $temp_distance = getDistance($point, $temp_point);
    if ($temp_distance < $min_distance) {
        $min_distance = $temp_distance;
        $res['num_x'] = $num['num_x'] + 1;
        $res['num_y'] = $num['num_x'];
    }

    $temp_point = getPointByNum($num['num_x'], $num['num_y'] + 1);
    $temp_distance = getDistance($point, $temp_point);
    if ($temp_distance < $min_distance) {
        $min_distance = $temp_distance;
        $res['num_x'] = $num['num_x'];
        $res['num_y'] = $num['num_x'] + 1;
    }

    $temp_point = getPointByNum($num['num_x'] + 1, $num['num_y'] + 1);
    $temp_distance = getDistance($point, $temp_point);
    if ($temp_distance < $min_distance) {
        $min_distance = $temp_distance;
        $res['num_x'] = $num['num_x'] + 1;
        $res['num_y'] = $num['num_x'] + 1;
    }

    return $res;
}

function getRanges($point, $radius_1, $radius_2) {
    $x = $point['longitude'];
    $y = $point['latitude'];

    if (! (is_numeric($x) && is_numeric($y) &&
        (is_numeric($radius_1) && is_numeric($radius_2)))) {
        return false;
    }

    $nums = getPrevPointNums($point);

    $radius_num_x = (floor($radius_2 * grad_in_km_x / delta_x) + 1);
    $radius_num_y = (floor($radius_2 * grad_in_km_y / delta_y) + 1);

    $range_1 = array();
    $range_2 = array();

    for ($i_x = -$radius_num_x; $i_x <= $radius_num_x; $i_x++) {
        for ($i_y = -$radius_num_y; $i_y <= $radius_num_y; $i_y++) {
            $temp_point = getPointByNum($nums['num_x'] + $i_x, $nums['num_y'] + $i_y);
            $distance = getDistance($point, $temp_point);
            if ($distance <= $radius_2) {
                $id = getPointId($nums['num_x'] + $i_x, $nums['num_y'] + $i_y);

                if ($distance <= $radius_1) {
                    $elem = array();
                    $elem['id'] = $id;
                    $elem['distance'] = $distance;
                    $range_1[] = $elem;
                } else {
                    $elem = array();
                    $elem['id'] = $id;
                    $elem['distance'] = $distance;
                    $range_2[] = $elem;
                }
            }
        }
    }

    $res = array();
    $res['range_1'] = $range_1;
    $res['range_2'] = $range_2;

    return $res;
}