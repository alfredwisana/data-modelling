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
        if ($header !== false) {
            // Loop through the CSV data and add to the RedisTimeSeries for each column
            while (($data = fgetcsv($file)) !== false) {
                // The first column is assumed to be the timestamp
                $timestamp = strtotime($data[0]);

                // Loop through the data columns (excluding the timestamp column)
                for ($i = 1; $i < count($data); $i++) {
                    $columnName = $header[$i];

                    // Use the column name as the time series key
                    $timeSeriesKey = $columnName;

                    // Get the corresponding value
                    $value = (float) $data[$i];

                    // Add data to RedisTimeSeries with the dynamic key
                    $redis->executeRaw(['TS.ADD', $timeSeriesKey, $timestamp, $value]);
                }
            }

            fclose($file);
        } else {
            // echo "Error reading CSV header.";
        }
    }
}


function displayCsvAsTable($csvFilePath) {
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
        echo'<tbody>';
        // Loop through the CSV data and add to the table
        while (($data = fgetcsv($file)) !== false) {
            
            echo '<tr>';
            
            // Loop through the data columns
            foreach ($data as $value) {
                echo '<td>' . $value . '</td>';
            }

            echo '</tr>';
        }
        echo'<tbody>';


        fclose($file);
    } else {
        echo "Error reading CSV file.";
    }
}
?>
