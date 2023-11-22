<?php

require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([]);

if (isset($_POST['functname'])) {
    $functname = $_POST['functname'];

    $keys = $redis->lrange('listcol', 0, -1);

    echo '<thead id="coltitle"> <tr>';
    #table head
    foreach ($keys as $columnName) {
        $columnName = preg_replace('/(?<!\ )[A-Z]/', ' $0', $columnName);
        echo '<th>' . $columnName . '</th>';
    }
    echo '</tr>';
    echo '</thead>';;

    $data = [];
    if ($functname === 'showraw') {
        foreach ($keys as $columnName) {

            $data[$columnName] = $redis->executeRaw(['TS.RANGE', $columnName, '-', '+']);
        }
    } elseif ($functname === 'aggregate') {
        # show the aggregrated data 
        foreach ($keys as $columnName) {
            $columnName = $columnName . "_compacted";
            $data[$columnName] = $redis->executeRaw(['TS.RANGE', $columnName, '-', '+']);
        }
    }


    if (count($data) > 0) {
        $max_length = max(array_map('count', $data));

        for ($i = 0; $i < $max_length; $i++) {
            echo "<tr>";
            foreach ($keys as $index => $key) {
                if ($functname === 'aggregate') {
                    $key = $key . "_compacted";
                }
                if ($index == 0 && isset($data[$key][$i][0])) {
                    $val = date('m/d/Y', $data[$key][$i][0] / 1000);
                    echo "<td>$val</td>";
                } else {
                    $val = (string) $data[$key][$i][1];
                    $val = number_format(floatval($val), 3);
                    echo "<td>$val</td>";
                }
            }


            echo "</tr>";
        }
    }
} else {
    echo 'Not Set';
}
?>