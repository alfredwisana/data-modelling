<?php
    require 'Predis/Predis/Autoload.php';

    use Predis\Client;
    $redis = new Client([]);
    if (!isset($_COOKIE['datacookie'])) {
        $defaultCookieValue = 'RAW';
        setcookie('datacookie', $defaultCookieValue, time() + 3600, '/'); 
        
    }
    if(isset($_POST['uploadcsv'])) {
        $file = $_FILES['fileToUpload']['tmp_name'];
        $handle = fopen($file, "r");
        $redis->executeRaw(['DEL', 'listname']);
        if ($handle !== FALSE) {
            $new_keys = [];
            $keys = fgetcsv($handle, 1000, ",");
            $count = 1;
            if ($keys !== FALSE) {
                foreach ($keys as $key) {
    
                    $redis->executeRaw(['DEL', $key]);
                    $redis->executeRaw(['TS.CREATE', $key]);
                    $rule = $key . "_" . "compacted";
                    $redis->executeRaw(['DEL', $rule]);
                    $redis->executeRaw(['TS.CREATE', $rule]);
                    $redis->rpush('listname', $key);
                    $new_keys[] = $key;
                }
                foreach ($new_keys as $key) {
                    if (strpos($key, 'Average') == True ){
                        $rule = $key . "_" . "compacted";
                        $redis->executeRaw(['TS.CREATERULE', $key, $rule, 'AGGREGATION', 'AVG', 31556952000]);
                    }
                    if (strpos($key, 'Min') == True || $key == 'dt') {
                        $rule = $key . "_" . "compacted";
                        $redis->executeRaw(['TS.CREATERULE', $key, $rule, 'AGGREGATION', 'MIN', 31556952000]);
                    }
                    if ( strpos($key, 'Max') == True) {
                        $rule = $key . "_" . "compacted";
                        $redis->executeRaw(['TS.CREATERULE', $key, $rule, 'AGGREGATION', 'MAX', 31556952000]);
                    }
                
            }   
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    
                    $timestamp = strtotime(str_replace('/', '-', $data[0])) * 1000;
                    if($timestamp < 0 ){ // time pada row data ke 1 hasilnya minus kita ubah ke 0
                        $timestamp = 0;
                    }
                    $maddArray = [];
                    foreach ($new_keys as $index => $key) {
                        if ($index == 0) {
                            $maddArray[] = $key;
                            $maddArray[] = $timestamp;
                            $maddArray[] = $timestamp;
                        } else {
                            $value = isset($data[$index]) ? floatval($data[$index]) : 0;
                            $maddArray[] = $key;
                            $maddArray[] = $timestamp;
                            $maddArray[] = $value;
                        }
                    }
                    $redis->executeRaw(array_merge(['TS.MADD'], $maddArray));
            
                    $count = $count + 1;
                }

            }
            
            fclose($handle);
        }
    }
    
    
    if (isset($_POST['buttonStatus'])) {
        $buttonStatus = $_POST["buttonStatus"];
    
        if ($buttonStatus == 'RAW') {
            $cookieValue = 'RAW';
        } else {
            $cookieValue = 'AGG';
        }
    
        // Set the cookie
        setcookie('datacookie', $cookieValue, time() + 3600, '/'); 
        echo '<script type="text/javascript">window.location.href = window.location.href;</script>';
    }
    
    $keys = $redis->lrange('listname', 0, -1);
    ob_start();
?>

<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <div class="center">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <h1><b>Masukan File CSV</b></h1>
                <p></p>
                <p></p>
                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" accept=".csv">
                <button type="submit" name="uploadcsv" class="btn btn-primary">upload</button>
            </div>
        </form>
        
    </div>
    <br>
    <div class="center">
    <form method="post">
        <button id="button1" name="buttonStatus" value="RAW">RAW</button>
        <button id="button2" name="buttonStatus" value="AGG">AGG</button>
    </form>
    </div>
    <br>
    <div class = 'centers'>
    <table>
    <tr>
        <?php foreach ($keys as $key): ?>
            <th><?php $key = preg_replace('/(?<!\ )[A-Z]/', ' $0', $key);
            echo $key; ?></th>
        <?php endforeach; ?>
    </tr>

    <?php
        $data = [];
        foreach ($keys as $key) {
            if (!isset($_COOKIE['datacookie'])) {
                $key = $key;
            }  
            elseif($_COOKIE['datacookie'] == 'AGG'){
                $key = $key . "_" . "compacted";
            }
            $data[$key] = $redis->executeRaw(['TS.RANGE', $key, '-', '+']);
        }
        if ( count($data) > 0){
        $max_length = max(array_map('count', $data));

        for ($i = 0; $i < $max_length; $i++) {
    ?>

        <tr>
            <?php foreach ($keys as $index => $key): ?>
                <td>
                    <?php 
                        if (!isset($_COOKIE['datacookie'])) {
                            $key = $key;
                        }  
                        elseif($_COOKIE['datacookie'] == 'AGG'){
                            $key = $key . "_" . "compacted";
                        }
                        if ($index == 0 && isset($data[$key][$i][0])) {
                            echo date('m-d-Y', $data[$key][$i][0] / 1000);
                        } else {
                            $value = (string) $data[$key][$i][1];
                            echo isset($value) ? number_format(floatval($value), 3) : '';

                        }
                    ?>
                </td>
            <?php endforeach; ?>
        </tr>
<?php
        }
?>


</table>
<?php
       } else{

        
    ?>
    <h2> NO DATA SAVED </h2>
    <?php 
        }
    ?>
    </div>


<?php
    ob_end_flush();
?>

</body>

</html>