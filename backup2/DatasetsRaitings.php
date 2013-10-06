<?php

include_once("funcs.php");
include_once("points.php");
include_once("csv_declaration.php");

define('km', 0.01605);

//Аптеки
    function getPharmaciesRaiting($location) {
        define('Pharmacies', 'datasets/pharmacy.csv');

        $distance = getPharmaciesRaitingFromDB($location);
        if (empty($distance)) {
            $data = parseCSV(Pharmacies, getCsvColumns(Pharmacies_id), ";");

            $distance = 1000;
            foreach ($data as $k => $v) {
                $coord = getCoordsByAddress($v['address']);
                $current_distance = getDistance($location, $coord);
                if ($current_distance <= 1) {
                    return 100;
                }
                if ($current_distance < $distance) {
                    $distance = $current_distance;
                }
            }
        }
        if ($distance <= 1) {
            return 100;
        } elseif ($distance > 5) {
            return 0;
        }
        return ((5 - $distance) / 4) * 100;
    }

    function getPharmaciesRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Pharmacies_id, $point_id);
        return $res;
    }

    function addPharmaciesToDB() {
        define('Pharmacies', 'datasets/pharmacy.csv');

        $data = parseCSV(Pharmacies, getCsvColumns(Pharmacies_id), ";");

        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);

            $ranges = getRanges($coord, 1, 5);
            foreach ($ranges['range_1'] as $k2 => $point) {
                addDataResult(Pharmacies_id, $point['id'], $point['distance']);
            }
            foreach ($ranges['range_2'] as $k2 => $point) {
                addDataResult(Pharmacies_id, $point['id'], $point['distance']);
            }
        }
    }

//Детские сады
    function getKindergartensRaiting($location) {
        define('Kindergartens', 'datasets/kindergartens.csv');

        $distance = getKindergartensRaitingFromDB($location);
        if (empty($distance)) {
            $data = parseCSV(Kindergartens, getCsvColumns(Kindergartens_id), ";");

            $distance = 1000;
            foreach ($data as $k => $v) {
                $coord = getCoordsByAddress($v['address']);
                $current_distance = getDistance($location, $coord);
                if ($current_distance <= 1) {
                    return 100;
                }
                if ($current_distance < $distance) {
                    $distance = $current_distance;
                }
            }
        }
        if ($distance <= 1) {
            return 100;
        } elseif ($distance > 5) {
            return 0;
        }
        return ((5 - $distance) / 4) * 100;
    }

    function getKindergartensRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Kindergartens_id, $point_id);
        return $res;
    }

    function addKindergartensToDB() {
        define('Kindergartens', 'datasets/kindergartens.csv');
        $data = parseCSV(Kindergartens, getCsvColumns(Kindergartens_id), ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);

            $ranges = getRanges($coord, 1, 8);
            foreach ($ranges['range_1'] as $k2 => $point) {
                addDataResult(Kindergartens_id, $point['id'], $point['distance']);
            }
            foreach ($ranges['range_2'] as $k2 => $point) {
                addDataResult(Kindergartens_id, $point['id'], $point['distance']);
            }
        }
    }

//Парки (включая парки не подведомственные)
    function getParksRaiting($location) {
        define('Parks', 'datasets/parks.csv');

        $distance = getParksRaitingFromDB($location);
        if (empty($distance)) {
            $data = parseCSV(Parks, getCsvColumns(Parks_id), ";");

            $distance = 1000;
            foreach ($data as $k => $v) {
                $coord = getCoordsByAddress($v['address']);
                $current_distance = getDistance($location, $coord);
                if ($current_distance <= 1) {
                    return 100;
                }
                if ($current_distance < $distance) {
                    $distance = $current_distance;
                }
            }
        }
        if ($distance <= 1) {
            return 100;
        } elseif ($distance > 5) {
            return 0;
        }
        return ((5 - $distance) / 4) * 100;
    }

    function getParksRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Parks_id, $point_id);
        return $res;
    }

    function addParksToDB() {
        define('Parks', 'datasets/parks.csv');
        $data = parseCSV(Parks, getCsvColumns(Parks_id), ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);

            $ranges = getRanges($coord, 1, 8);
            foreach ($ranges['range_1'] as $k2 => $point) {
                addDataResult(Parks_id, $point['id'], $point['distance']);
            }
            foreach ($ranges['range_2'] as $k2 => $point) {
                addDataResult(Parks_id, $point['id'], $point['distance']);
            }
        }
    }

//Кинотеатры
    function getCinemaRaiting($location) {
        define('Cinema', 'datasets/cinema.csv');

        $distance = getParksRaitingFromDB($location);
        if (empty($distance)) {
            $data = parseCSV(Cinema, getCsvColumns(Cinema_id), ";");

            $distance = 1000;
            foreach ($data as $k => $v) {
                $coord = getCoordsByAddress($v['address']);
                $current_distance = getDistance($location, $coord);
                if ($current_distance <= 2) {
                    return 100;
                }
                if ($current_distance < $distance) {
                    $distance = $current_distance;
                }
            }
        }
        if ($distance <= 2) {
            return 100;
        } elseif ($distance > 10) {
            return 0;
        }
        return ((10 - $distance) / 9) * 100;
    }

    function getCinemaRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Cinema_id, $point_id);
        return $res;
    }

    function addCinemaToDB() {
        define('Cinema', 'datasets/cinema.csv');
        $data = parseCSV(Cinema, getCsvColumns(Cinema_id), ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);

            $ranges = getRanges($coord, 1, 10);
            foreach ($ranges['range_1'] as $k2 => $point) {
                addDataResult(Cinema_id, $point['id'], $point['distance']);
            }
            foreach ($ranges['range_2'] as $k2 => $point) {
                addDataResult(Cinema_id, $point['id'], $point['distance']);
            }
        }
    }

//Станции метрополитена
    function getMetroRaiting($location) {
        define('Metro', 'datasets/metro.csv');

        $distance = getParksRaitingFromDB($location);
        if (empty($distance)) {
            $data = parseCSV(Metro, getCsvColumns(Metro_id), ";");

            $distance = 1000;
            foreach ($data as $k => $v) {
                $coord = getCoordsByAddress($v['address']);
                $current_distance = getDistance($location, $coord);
                if ($current_distance <= 0.5) {
                    return 100;
                }
                if ($current_distance < $distance) {
                    $distance = $current_distance;
                }
            }
        }
        if ($distance <= 0.5) {
            return 100;
        } elseif ($distance > 4) {
            return 0;
        }
        return ((4 - $distance) / 3.5) * 100;
    }

    function getMetroRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Metro_id, $point_id);
        return $res;
    }

    function addMetroToDB() {
        define('Metro', 'datasets/metro.csv');
        $data = parseCSV(Metro, getCsvColumns(Metro_id), ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);

            $ranges = getRanges($coord, 0.5, 4);
            foreach ($ranges['range_1'] as $k2 => $point) {
                addDataResult(Metro_id, $point['id'], $point['distance']);
            }
            foreach ($ranges['range_2'] as $k2 => $point) {
                addDataResult(Metro_id, $point['id'], $point['distance']);
            }
        }
    }

//Площадки спортивные универсальные
    function getSportRaiting($location) {
        define('Sport', 'datasets/sport.csv');

        $Sport_columns = array(
            'id',
            'uid',
            'code',
            'old_id',
            'name',
            'address',
            'price',
            'x',
            'y',
            'bti',
            'sport_id',
            'sport_type',
            'sport_name',
            'prokat',
            'work_time',
            'pokritie',
            'light',
            'free_train',
            'cost',
            'latitude',
            'longitude',
            'obj_id',
            'obj_type',
            'owner',
            'okrug',
            'rajon',
            'tech_service',
            'razdevalka',
            'noize',
            'food',
            'toilet',
            'wifi',
            'bankomat',
            'medicine',
            'OGRN',
            'organization',
            'phone',
            'site',
            'email',
            'status',
            'comment');
        $data = parseCSV(Sport, $Sport_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= km) {
                return 100;
            }
            if ($distance < 5 * km) {
                $temp = ((5 * km - $distance) / 4) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
    }

//Розничные рынки
    function getMarketRaiting($location) {
        define('Market', 'datasets/market.csv');

        $Market_columns = array(
            'id',
            'name',
            'sobstven',
            'company',
            'company_address',
            'address',
            'type');
        $data = parseCSV(Market, $Market_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= km) {
                return 100;
            }
            if ($distance < 5 * km) {
                $temp = ((5 * km - $distance) / 4) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
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
                return 100;
            }
            if ($current_distance < $distance) {
                $distance = $current_distance;
            }
        }
    }
    if ($distance <= $minDistance) {
        return 100;
    } elseif ($distance > $maxDistance) {
        return 0;
    }
    return (($maxDistance - $distance) / ($maxDistance - $minDistance)) * 100;
}


function getRaitingFromDB($location, $datasetId) {
    $point = getNearestPoint($location);
    $point_id = getPointId($point['num_x'], $point['num_y']);

    $res = getDataResult($datasetId, $point_id);
    return $res;
}