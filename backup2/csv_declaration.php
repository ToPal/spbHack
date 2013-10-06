<?php

// id массивов данных
define("Pharmacies_id", 1);
define("Kindergartens_id", 2);
define("Parks_id", 3);
define("Cinema_id", 4);
define("Metro_id", 5);
define("Sport_id", 6);
define("Market_id", 7);


function getCsvColumns($datasetId) {
    switch ($datasetId) {
        case Pharmacies_id:
            return array(
                'id',
                'name',
                'address',
                'phone',
                'work_time',
                'company',
                'type',
                'weekend_type',
                'comment');
        case Kindergartens_id:
            return array(
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
        case Parks_id:
            return array(
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
        case Cinema_id:
            return array(
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
        case Metro_id:
            return array(
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
        default: return false;
    }
}
