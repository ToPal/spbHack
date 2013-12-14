<?php
    include_once("qrs.php");

    define('files_dir', '../datasets/');

    // 1. загружаем файлы из интернета
    $files = getFilesAddressesFromDB();
    foreach ($files as $file) {
        $file_address = files_dir.$file['Filename'];

        if (downloadFile($file['Url'], $file_address)) {
            // 2. каждый из них открываем, парсим, заливаем в таблицу Data:
            updateDataset($file['ID'], $file_address);
        }
    }



    // Обработка
    // 1. Просчитываем Result всех точек, у которых взведен флаг "новый" и меняем их флаг на "текущий"
    // 2. Для каждой точки, у которой Lastupdate меньше, чем у соответствующего database_id:
    //   2.1 Если есть Result, использующий эту точку, то просчитываем все точки, которые лежат вокруг соответствующего Result
    //   2.2 Удаляем данную строку


function updateDataset($datasetId, $file_address) {
    //   2.1 если элемента в этой точке ранее не было, то взводим флаг "новый"
    //   2.2 если элемент в данной точке был, то обновляем существующую запись без изменения флага
    $data = parseCSV($file_address, getCsvColumns($datasetId), ';');
    $current_data = getDatasetRowsByDatasetId($datasetId);

    foreach ($data as $row) {
        $rowText = implode(";", $row);

        $datasetRowId = getRowInDatasetArray($rowText, $current_data);
        if ($datasetId == false) {
            addDatasetRow($datasetRowId, $rowText);
        } else {
            updDatasetRow($datasetId, $rowText, true);
        }
    }
}

function getRowInDatasetArray($row, $datasetArray) {
    foreach ($datasetArray as $datasetRow) {
        if ($row == $datasetRow[Data_String]) {
            return $datasetRow[Data_ID];
        }
    }
    return FALSE;
}

function downloadFile($source, $destination) {
    $content = file_get_contents($source);
    if ($content == FALSE) {
        return FALSE;
    }

    $f = fopen( "$destination", "w" );
    if ($f == FALSE) {
        return FALSE;
    }

    if (fwrite( $f, $content ) == FALSE) {
        return FALSE;
    }

    if (fclose( $f ) == FALSE) {
        return FALSE;
    }

    return TRUE;
}