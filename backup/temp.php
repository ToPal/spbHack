<?php
    include_once('DatasetsRaitings.php');

    $location['latitude'] = 37.735268;
    $location['longitude'] = 55.693351;
    $r = getKindergartensRaiting($location);
var_dump($r);