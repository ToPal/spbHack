<?php
    include_once("DatasetsRaitings.php");
    include_once("funcs.php");

    function getSocialRaiting($location) {
        if (count($location) != 2) {
            throw new Exception("Неверные параметры в функции определения социального рейтинга");
        }

        $raiting = 0;
        $raiting += getPharmaciesRaiting($location);
        //$raiting += getKindergartensRaiting($location);

        return $raiting / 2;
    }

    function getRecreationRaiting($location) {
        if (count($location) != 2) {
            throw new Exception("Неверные параметры в функции определения рекреационного рейтинга");
        }

        $raiting = 0;
        $raiting += getParksRaiting($location);
        //$raiting += getCinemaRaiting($location);
        //$raiting += getSportRaiting($location);

        return $raiting / 3;
    }

    function getInfrastructureRaiting($location) {
        if (count($location) != 2) {
            throw new Exception("Неверные параметры в функции определения инфраструктурного рейтинга");
        }

        $raiting = 0;
        //$raiting += 0.3 * getGasStationRaiting($location);
        $raiting += getMetroRaiting($location);
        //$raiting += getMarketRaiting($location);

        return $raiting / 2;
    }

    function getCumulativeRaiting($location) {
        $localRaitings = array();
        $localRaitings['socialRaiting'] = round(getSocialRaiting($location));
        $localRaitings['recreationRaiting'] = round(getRecreationRaiting($location));
        $localRaitings['infrastructureRaiting'] = round(getInfrastructureRaiting($location));

        $res = array();
        $res['localRaitings'] = $localRaitings;
        $res['raiting'] = round(($localRaitings['socialRaiting'] + $localRaitings['recreationRaiting'] + $localRaitings['infrastructureRaiting']) / 3);

        return $res;
    }