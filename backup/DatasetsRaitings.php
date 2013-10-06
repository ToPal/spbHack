<?php

include_once("funcs.php");
include_once("points.php");

define('km', 0.01605);

// id массивов данных
define("Pharmacies_id", 1);
define("Kindergartens_id", 2);
define("Parks_id", 3);
define("Cinema_id", 4);
define("GasStation_id", 5);
define("Metro_id", 6);
define("Sport_id", 7);
define("Market_id", 8);

//Аптеки
    function getPharmaciesRaiting($location) {
        define('Pharmacies', 'datasets/pharmacy.csv');

        $distance = getPharmaciesRaitingFromDB($location);
        if (!empty($distance)) {
            if ($distance <= 1) {
                return 100;
            } elseif ($distance > 5) {
                return 0;
            }
            return ((5 - $distance) / 4) * 100;
        }

        $Pharmacies_columns = array(
            'id',
            'name',
            'address',
            'phone',
            'work_time',
            'company',
            'type',
            'weekend_type',
            'comment');
        $data = parseCSV(Pharmacies, $Pharmacies_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= 1) {
                return 100;
            }
            if ($distance < 5) {
                $temp = ((5 - $distance) / 4) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
    }

    function getPharmaciesRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Pharmacies_id, $point_id);
        return $res;
    }

    function addPharmaciesToDB() {
        define('Pharmacies', 'datasets/pharmacy.csv');

        $Pharmacies_columns = array(
            'id',
            'name',
            'address',
            'phone',
            'work_time',
            'company',
            'type',
            'weekend_type',
            'comment');
        $data = parseCSV(Pharmacies, $Pharmacies_columns, ";");

        $res = 0;
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

        $res = getKindergartensRaitingFromDB($location);
        if (!empty($res)) {
            return $res;
        }

        $Kindergartens_columns = array(
            'id',
            'name',
            'label',
            'address',
            'x',
            'y',
            'bti',
            'cad_no',
            'street_bti',
            'house_bti',
            'hadd_bti',
            'org_form',
            'type',
            'class',
            'phone',
            'site',
            'owner');
        $data = parseCSV(Kindergartens, $Kindergartens_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= km) {
                return 100;
            }
            if ($distance < 10 * km) {
                $temp = ((10 * km - $distance) / 9) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
    }

    function getKindergartensRaitingFromDB($location) {
        $point = getNearestPoint($location);
        $point_id = getPointId($point['num_x'], $point['num_y']);

        $res = getDataResult(Kindergartens_id, $point_id);
        return $res;
    }

    function addKindergartensToDB() {
        define('Kindergartens', 'datasets/kindergartens.csv');

        $Kindergartens_columns = array(
            'id',
            'name',
            'label',
            'address',
            'x',
            'y',
            'bti',
            'cad_no',
            'street_bti',
            'house_bti',
            'hadd_bti',
            'org_form',
            'type',
            'class',
            'phone',
            'site',
            'owner');
        $data = parseCSV(Kindergartens, $Kindergartens_columns, ";");

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

        $Parks_columns = array(
            'id',
            'name',
            'label',
            'address',
            'x',
            'y',
            'bti',
            'cad_no',
            'street_bti',
            'house_bti',
            'hadd_bti',
            'adm_okrug',
            'area',
            'urid_addr',
            'phone',
            'fax',
            'site',
            'email');
        $data = parseCSV(Parks, $Parks_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= km) {
                return 100;
            }
            if ($distance < 15 * km) {
                $temp = ((15 * km - $distance) / 14) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
    }

//Кинотеатры
    function getCinemaRaiting($location) {
        define('Cinema', 'datasets/cinema.csv');

        $Cinema_columns = array(
            'id',
            'name',
            'label',
            'address',
            'x',
            'y',
            'bti',
            'cad_no',
            'street_bti',
            'house_bti',
            'hadd_bti',
            'adm_okrug',
            'area',
            'urid_addr',
            'phone',
            'fax',
            'site',
            'email');
        $data = parseCSV(Cinema, $Cinema_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= km) {
                return 100;
            }
            if ($distance < 15 * km) {
                $temp = ((15 * km - $distance) / 14) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
    }

//Список АЗС города Москвы, реализующих моторное топливо, несоответствующее установленным экологическим требованиям
    function getGasStationRaiting($location) {
        define('GasStation', 'datasets/gasstation.csv');

        $GasStation_columns = array(
            'code',
            'gasStation',
            'address',
            'company',
            'expert',
            'brand',
            'octan',
            'sera',
            'benzol',
            'smaz',
            'arom_uglev',
            'pollution',
            'checking_date');
        $data = parseCSV(GasStation, $GasStation_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress($v['address']);
            $distance = getDistance($location, $coord);
            if ($distance <= km) {
                return 100;
            }
            if ($distance < 25 * km) {
                $temp = ((25 * km - $distance) / 24) * 100;
                if ($temp > $res) {
                    $res = $temp;
                }
            }
        }

        return $res;
    }

//Станции метрополитена
    function getMetroRaiting($location) {
        define('Metro', 'datasets/metro.csv');

        $Metro_columns = array(
            'id',
            'name',
            'label',
            'address',
            'x',
            'y',
            'bui_bti',
            'cad_no',
            'street_bti',
            'house_bti',
            'hadd_bti',
            'line',
            'status',
            'vestibul',
            'time1',
            'time2',
            'BPA_count',
            'remont_date',
            'escalator_type',
            'escalator_lenght',
            'escalator_count',
            'moddate',
            'moduser',
            'BPA_type');
        $data = parseCSV(Metro, $Metro_columns, ";");

        $res = 0;
        foreach ($data as $k => $v) {
            $coord = getCoordsByAddress("метро ".$v['name']);
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