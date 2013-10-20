<?php
    include_once("DatasetsRaitings.php");
    include_once("funcs.php");
    include_once("csv_declaration.php");

    function getDefaultCoeffs() {
        $coeffs = array();
        for ($i = 1; $i <= 7; $i++) {
            $coeffs[$i] = 0.5;
        }
        return $coeffs;
    }

    function calc_raitings($coeffs, $location) {
        $res = array();
        foreach ($coeffs as $datasetId => $coeff) {
            if ($coeff != 0) {
                $res[$datasetId] = getRaitingByDatasetId($datasetId, $location);
            }
        }
        return $res;
    }

    function getLocalRaiting($raitings, $coeffs, $datasets) {
        $raiting = 0;
        $divider = 0;
        $multiplier = 1;
        foreach ($datasets as $k => $dataset) {
            if ($coeffs[$dataset] != 1) {
                $raiting += $raitings[$dataset] * $coeffs[$dataset];
                $divider += $coeffs[$dataset];
            } else {
                $multiplier *= $raitings[$dataset] / 100;
            }
        }
        if ($divider == 0) $divider = 1;

        return $raiting * $multiplier / $divider;
    }

    function getFullRaiting($raitings, $coeffs) {
        $raiting = 0;
        $divider = 0;
        $multiplier = 1;
        foreach ($coeffs as $datasetId => $coeff) {
            if ($coeff != 1) {
                $raiting += $raitings[$datasetId] * $coeff;
                $divider += $coeff;
            } else {
                $multiplier *= $raitings[$datasetId] / 100;
            }
        }
        if ($divider == 0) $divider = 1;

        return $raiting * $multiplier / $divider;
    }

    function getCumulativeRaiting($location) {
        $coeffs = getDefaultCoeffs();
        $raitings = calc_raitings($coeffs, $location);

        $localRaitings = array();
        $localRaitings['socialRaiting'] = round(getLocalRaiting($raitings, $coeffs, array(Pharmacies_id, Kindergartens_id)));
        $localRaitings['infrastructureRaiting'] = round(getLocalRaiting($raitings, $coeffs, array(Metro_id)));
        $localRaitings['recreationRaiting'] = round(getLocalRaiting($raitings, $coeffs, array(Parks_id, Cinema_id, Sport_id)));

        $res = array();
        $res['localRaitings'] = $localRaitings;
        $res['raiting'] = getFullRaiting($raitings, $coeffs);

        return $res;
    }