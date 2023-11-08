<?php

require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port'  => 6379
]);

function lpush($nama)
{
    global $redis;
    $len = $redis->llen('people');
    if ($len < 10) {
        $redis->lpush('people', $nama);
        return $nama . ' has been left pushed';
    } else {
        return  "List already has 10 data";
    }
}

function rpush($nama)
{
    global $redis;
    $len = $redis->llen('people');
    if ($len < 10) {
        $redis->rpush('people', $nama);
        return $nama . ' has been right pushed';
    } else {
        return "List already has 10 data";
    }
}
function lpop()
{
    global $redis;

    $lpop = $redis->lpop('people');
    return $lpop . ' has been left popped';
}
function rpop()
{
    global $redis;
    $rpop = $redis->rpop('people');
    return $rpop . ' has been left popped';
}



if (isset($_POST['action'])) {
    // $result = array(
    //     'message' => ""
    // );
    $message = '';
    $action = $_POST['action'];
    if (function_exists($action)) {
        if ($action === 'lpush') {
            if (isset($_POST['nama'])) {
                $value = $_POST['nama'];
                if (!empty($value)) {
                    $message=lpush($value);
                    
                }
            }
        } elseif ($action === 'lpop') {
            $message= lpop();
        } elseif ($action === 'rpush') {
            if (isset($_POST['nama'])) {
                $value = $_POST['nama'];
                if (!empty($value)) {
                    $message=rpush($value);
                }
            }
        } elseif ($action  === 'rpop') {
            $message= rpop();
        }
    } else {
        echo '<script type="text/javascript">alert("No Function Specified");</script>';
    }
    // echo json_encode($result);
    echo $message;
}
