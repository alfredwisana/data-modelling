<?php

require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([]);

if (isset($_FILES['file']['error']) && $_FILES['file']['error'] == UPLOAD_ERR_OK && isset($_POST['functname'])) {
    // Specify the directory where you want to save the uploaded file
    $filepath = 'csv_file/' . basename($_FILES['file']['name']);
    $functname = $_POST['functname'];

    if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
        if ($functname === 'showraw') {
            rawdata($filepath);
        } elseif ($functname === 'aggregate') {
            aggregate($filepath);
        }
    }
} else {
    echo $_POST['functname'];
}


function rawdata($filepath)
{
    $file = fopen($filepath, 'r');

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
    }
}


function aggregate($filepath)
{
    global $redis;


    $file = fopen($filepath, 'r');

    if ($file !== false) {


        $header = fgetcsv($file);


        echo '<thead> <tr>';
        foreach ($header as $columnName) {

            $sourcekey = $columnName;
            $destkey = $sourcekey."_compacted";
            echo $sourcekey;
            echo $destkey;
            // COMPACTION RULES
            
            
            echo '<th>' . $columnName . '</th>';
        }
        echo '</tr>';
        echo '</thead>';;

        // foreach ($header as $columnName) {
        //     echo '<tr>';
        //     if ($columnName !== 'dt') {
        //         $key = $columnName . "_compacted";
        //         $data = $redis->executeRaw(['TS.RANGE', $key, '-', '+']);
        //         echo '<td>' . $data . '</td>';

        //     }
          
        //     echo '</tr>';
        // }

        fclose($file);
    }
}
