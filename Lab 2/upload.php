<?php
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    // Specify the directory where you want to save the uploaded file
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Convert CSV to RedisTimeSeries
        convertCsvToTimeSeries($uploadFile);

        echo "File uploaded and converted successfully!";
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded.";
}


function convertCsvToTimeSeries($csvFilePath)
{
    
    global $redis;
    // Your RedisTimeSeries key
    $timeSeriesKey = 'your_timeseries_key';

    // Open the CSV file for reading
    $file = fopen($csvFilePath, 'r');

    if ($file !== false) {
        // Skip the header row if it exists
        fgetcsv($file);

        // Loop through the CSV data and add to the RedisTimeSeries
        while (($data = fgetcsv($file)) !== false) {
            // Assuming the first column is the timestamp and the second column is the value
            $timestamp = strtotime($data[0]); // Convert the timestamp to Unix timestamp
            $value = (float) $data[1]; // Convert the value to a float

            // Add data to RedisTimeSeries
            $redis->rawCommand('TS.ADD', $timeSeriesKey, $timestamp, $value);
        }

        fclose($file);
    }
}


?>