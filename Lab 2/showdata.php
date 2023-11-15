<?php

require 'Predis/Predis/Autoload.php';

use Predis\Client;
$redis = new Client([]);

if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK && isset($_POST['functname'])) {
    // Specify the directory where you want to save the uploaded file
    $filepath = 'csv_file/' . basename($_FILES['file']['name']);

    $functname = $_POST['functname'];

    if($functname === 'showraw'){
        rawdata($filepath);
    }elseif($functname === 'aggregate'){
        aggregate($filepath);
    }
} 


function rawdata($filepath){
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
    } 
}


function aggregate($filepath){
    global $redis;


    $file = fopen($filepath, 'r');

    if ($file !== false) {
    
       
        $header = fgetcsv($file);
        
       
        echo '<thead> <tr>';
        foreach ($header as $columnName) {
            
            $sourcekey = $columnName;
            $destkey = $columnName."_compacted";
            $bucketsize = 365*24*60*60*1000;
            // COMPACTION RULES
            if (strpos($columnName,'Average') !== false){
                $redis->executeRaw(['TS.CREATERULE', $sourcekey, $destkey, "AGGREGATION avg ".$bucketsize]);
            }elseif(strpos($columnName,'MIN') !== false){
                $redis->executeRaw(['TS.CREATERULE', $sourcekey, $destkey, "AGGREGATION min ".$bucketsize]);
            }elseif(strpos($columnName,'MAX') !== false){
                $redis->executeRaw(['TS.CREATERULE', $sourcekey, $destkey, "AGGREGATION max ".$bucketsize]);
            }
                echo '<th>'.$columnName.'</th>';  
        }
        echo '</tr>';
        echo '</thead>';
        echo'<tbody>';

        foreach ($header as $columnName) {
            echo '<tr>';
            $key = $columnName."_compacted";
            $data = $redis->executeRaw(['TS.RANGE', $key, '-', '+']);

            echo '<td>' . $data . '</td>';

            echo '</tr>';
            
        }
        echo '</tbody>';
        fclose($file);
    } 
}
