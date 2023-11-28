<?php

// Alfred Wisana -- c14210177
// Used for upload the csv file

require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([]);

if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'csv_file/';
    // move the file into a folder in the server (localhost)
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        convertCsvToTimeSeries($uploadFile);
        displayCsvAsTable($uploadFile);
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded.";
}



function convertCsvToTimeSeries($csvFilePath)
{
    global $redis;

    $file = fopen($csvFilePath, 'r');

    if ($file !== false) {
        $header = fgetcsv($file);

        if ($header !== false) {
            $firstDataRow = fgetcsv($file);
            if ($firstDataRow !== false) {
                
                #creating a list contain of the original header name
                $redis->executeRaw(['DEL', 'listcol']);
                for ($i = 0; $i < count($firstDataRow); $i++) {
                    $columnName = $header[$i];

                    $sourcekey = $columnName;

                    $valueBase = (float) $firstDataRow[$i];
                    $period = 31556952000;


                    $redis->rpush('listcol', $sourcekey);
                    $redis->executeRaw(['DEL', $sourcekey]);
                    $redis->executeRaw(['TS.ADD', $sourcekey, 0, $valueBase]);
                    $rulekey = $sourcekey . "_compacted";
                    $redis->executeRaw(['DEL', $rulekey]);
                    $redis->executeRaw(['TS.CREATE', $rulekey]);
                    # Time Series Compaction Rules Creation
                    if (strpos($sourcekey, 'Average') !== false) {
                        $redis->executeRaw(['TS.CREATERULE', $sourcekey, $rulekey, 'AGGREGATION', 'avg', $period]);
                        echo "Avg";
                    } elseif (strpos($sourcekey, 'Min') !== false || $sourcekey === 'dt') {
                        $redis->executeRaw(['TS.CREATERULE', $sourcekey, $rulekey, 'AGGREGATION', 'min', $period]);
                        echo "Min";
                    } elseif (strpos($sourcekey, 'Max') !== false) {
                        $redis->executeRaw(['TS.CREATERULE', $sourcekey, $rulekey, 'AGGREGATION', 'max', $period]);
                        echo "Max";
                    }
                }

                while (($data = fgetcsv($file)) !== false) {

                    # Add Data to the time series

                    for ($i = 0; $i < count($data); $i++) {
                        $columnName = $header[$i];

                        $sourcekey = $columnName;

                        $value = (float) $data[$i];

                        $timestamp = strtotime(str_replace('/', '-', $data[0])) * 1000;
                        if ($timestamp < 0) { // time pada row data ke 1 hasilnya minus kita ubah ke 0
                            $timestamp = 0;
                        }

                        $redis->executeRaw(['TS.ADD', $sourcekey, $timestamp, $value]);
                    }
                }
            }

            fclose($file);
        } 
    }
}



function displayCsvAsTable($csvFilePath)
{
    // Open the CSV file for reading
    $file = fopen($csvFilePath, 'r');

    if ($file !== false) {

        $header = fgetcsv($file);
        echo '<thead id="coltitle">';
        // Display the column name
        echo '<tr>';
        foreach ($header as $columnName) {
            $columnName = preg_replace('/(?<!\ )[A-Z]/', ' $0', $columnName);
            echo '<th>' . $columnName . '</th>';
        }
        echo '</tr>';
        echo '</thead>';

        // Loop through the CSV data and add to the table
        while (($data = fgetcsv($file)) !== false) {

            echo '<tr>';

            // Loop through the data columns
            foreach ($data as $value) {
                echo '<td>' . $value . '</td>';
            }

            echo '</tr>';
        }



        fclose($file);
    } else {
        echo "Error reading CSV file.";
    }
}
