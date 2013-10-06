<?php
    include_once('DatasetsRaitings.php');

//    $location['latitude'] = 37.735268;
//    $location['longitude'] = 55.693351;
//    $r = get($location);
//var_dump($r);
set_time_limit(0);
    addDataToDB('datasets/pharmacy.csv', Pharmacies_id, 1, 5);
    addDataToDB('datasets/kindergartens.csv', Kindergartens_id, 0.5, 4);
    addDataToDB('datasets/parks.csv', Parks_id, 2, 10);
    addDataToDB('datasets/cinema.csv', Cinema_id, 1, 7);
    addDataToDB('datasets/metro.csv', Metro_id, 0.5, 4);
    addDataToDB('datasets/sport.csv', Sport_id, 0.5, 4);
    addDataToDB('datasets/market.csv', Market_id, 1, 8);
