<?php

require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([]);

if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    // Specify the directory where you want to save the uploaded file
    $uploadDir = 'csv_file/';
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Convert CSV to RedisTimeSeries
        convertCsvToTimeSeries($uploadFile);
        displayCsvAsTable($uploadFile);
        // echo "File uploaded and converted successfully!";
    } else {
        // echo "Error uploading file.";
    }
} else {
    // echo "No file uploaded.";
}



function convertCsvToTimeSeries($csvFilePath) {
    global $redis;

    // Open the CSV file for reading
    $file = fopen($csvFilePath, 'r');

    if ($file !== false) {
        // Read the header (first row) to get the column names
        $header = fgetcsv($file);

        // Check if the header was successfully read
        if ($header !== false) {
            // Read the first data row to use as a reference
            $firstDataRow = fgetcsv($file);
            if ($firstDataRow !== false) {
                // The first column is assumed to be the timestamp
                $timestampBase = strtotime($firstDataRow[0]);

                // Loop through the data columns (excluding the timestamp column)
                for ($i = 1; $i < count($firstDataRow); $i++) {
                    $columnName = $header[$i];

                    // Use the column name as the time series key
                    $sourcekey = $columnName;

                    // Get the corresponding value from the first row
                    $valueBase = (float) $firstDataRow[$i];
                    $period = 365 * 24 * 60 * 60 * 1000;

                    // Add the base data to RedisTimeSeries with timestamp 0
                    $redis->executeRaw(['TS.ADD', $sourcekey, 0, $valueBase]);
                    $destkey =$sourcekey."_compacted";
                    $redis->executeRaw(['TS.CREATE', $destkey]);
                    if (strpos($sourcekey, 'Average') !== false) {
                        $redis->executeRaw(['TS.CREATERULE', $sourcekey, $destkey, 'AGGREGATION', 'avg', $period]);
                        echo "Avg";
                    } elseif (strpos($sourcekey, 'Min') !== false) {
                        $redis->executeRaw(['TS.CREATERULE', $sourcekey, $destkey, 'AGGREGATION', 'min', $period]);
                        echo "Min";
                    } elseif (strpos($sourcekey, 'Max') !== false) {
                        $redis->executeRaw(['TS.CREATERULE', $sourcekey, $destkey, 'AGGREGATION', 'max', $period]);
                        echo "Max";
                    }
                }

                // Loop through the remaining rows and add to the RedisTimeSeries
                while (($data = fgetcsv($file)) !== false) {
                    // The first column is assumed to be the timestamp
                    $timestamp = strtotime($data[0]);

                    // Loop through the data columns (excluding the timestamp column)
                    for ($i = 1; $i < count($data); $i++) {
                        $columnName = $header[$i];

                        // Use the column name as the time series key
                        $sourcekey = $columnName;

                        // Get the corresponding value
                        $value = (float) $data[$i];

                        // Calculate the adjusted timestamp based on the difference from the base timestamp
                        $adjustedTimestamp = ($timestamp - $timestampBase)*1000;

                        // Add data to RedisTimeSeries with the dynamic key
                        $redis->executeRaw(['TS.ADD', $sourcekey, $adjustedTimestamp, $value]);
                    }
                }
            }

            fclose($file);
        } else {
            // Handle the case where the header could not be read
            // echo "Error reading CSV header.";
        }
    }
}



function displayCsvAsTable($csvFilePath)
{
    // Open the CSV file for reading
    $file = fopen($csvFilePath, 'r');

    if ($file !== false) {

        // Read the header (first row) to get the column names
        $header = fgetcsv($file);
        echo '<thead>';
        // Display the header row
        echo '<tr>';
        foreach ($header as $columnName) {
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
?>