<?php

include_once("funcs.php");
include_once("points.php");
include_once("csv_declaration.php");
include_once("Nearest.php");

define('km', 0.01605);

$current_location = null;

function getRaitingByDatasetId($datasetId, $location) {
    switch ($datasetId) {
        case Pharmacies_id:
            return getPharmaciesRaiting($location);
        case Kindergartens_id:
            return getKindergartensRaiting($location);
        case Parks_id:
            return getParksRaiting($location);
        case Cinema_id:
            return getCinemaRaiting($location);
        case Metro_id:
            return getMetroRaiting($location);
        case Sport_id:
            return getSportRaiting($location);
        case Market_id:
            return getMarketRaiting($location);
        default: return false;
    }
}

//Аптеки
    function getPharmaciesRaiting($location) {
        return getRaiting($location, 'datasets/pharmacy.csv', Pharmacies_id, 1, 5);
    }

//Детские сады
    function getKindergartensRaiting($location) {
        return getRaiting($location, 'datasets/kindergartens.csv', Kindergartens_id, 0.5, 4);
    }

//Парки (включая парки не подведомственные)
    function getParksRaiting($location) {
        return getRaiting($location, 'datasets/parks.csv', Parks_id, 2, 10);
    }

//Кинотеатры
    function getCinemaRaiting($location) {
        return getRaiting($location, 'datasets/cinema.csv', Cinema_id, 1, 7);
    }

//Станции метрополитена
    function getMetroRaiting($location) {
        return getRaiting($location, 'datasets/metro.csv', Metro_id, 0.5, 4);
    }

//Площадки спортивные универсальные
    function getSportRaiting($location) {
        return getRaiting($location, 'datasets/sport.csv', Sport_id, 0.5, 4);
    }

//Розничные рынки
    function getMarketRaiting($location) {
        return getRaiting($location, 'datasets/market.csv', Market_id, 1, 8);
    }





function parseCSV($filename, $columns, $delimiter) {
    $handle = fopen($filename, "r");
    if ($handle == FALSE) {
        throw new Exception('Ошибка в процессе открытия файла');
    }
    if (($data = fgetcsv($handle, 0, $delimiter)) == FALSE) {
        throw new Exception('Файл не содержит ни одной строки');
    }

    $res = array();

    while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
        $row = array();
        for ($col = 0; $col < count($columns); $col++) {
            $row[$columns[$col]] = $data[$col];
        }

        $res[] = $row;
    }
    fclose($handle);
    return $res;
}






function getRaiting($location, $csvName, $datasetId, $minDistance, $maxDistance) {
    $distance = getRaitingFromDB($location, $datasetId);
    if (empty($distance)) {
        $data = parseCSV($csvName, getCsvColumns(Metro_id), ";");

        $distance = 1000;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $current_distance = getDistance($location, $coord);
            if ($current_distance <= $minDistance) {
                setNearest($datasetId, $current_distance);
                return 100;
            }
            if ($current_distance < $distance) {
                $distance = $current_distance;
            }
        }
    }
    if ($distance <= $minDistance) {
        setNearest($datasetId, $distance);
        return 100;
    } elseif ($distance > $maxDistance) {
        return 0;
    }
    setNearest($datasetId, $distance);
    return (($maxDistance - $distance) / ($maxDistance - $minDistance)) * 100;
}


function getRaitingFromDB($location, $datasetId) {
    $point = getNearestPoint($location);
    $point_id = getPointId($point['num_x'], $point['num_y']);

    $res = getDataResult($datasetId, $point_id);
    return $res;
}


function addDataToDB($csvName, $datasetId, $minDistance, $maxDistance) {
    $data = parseCSV($csvName, getCsvColumns($datasetId), ";");

    $res = 0;
    foreach ($data as $k => $v) {
        $coord = getCoordsByAddress($v['address']);

        $ranges = getRanges($coord, $minDistance, $maxDistance);
        foreach ($ranges['range_1'] as $k2 => $point) {
            addDataResult($datasetId, $point['id'], $point['distance']);
        }
        foreach ($ranges['range_2'] as $k2 => $point) {
            addDataResult($datasetId, $point['id'], $point['distance']);
        }
    }
}