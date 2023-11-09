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

        return [1, $nama . ' has been left pushed'];
    } else {

        return [0, "List already has 10 data"];
    }
}

function rpush($nama)
{

    global $redis;
    $len = $redis->llen('people');
    if ($len < 10) {
        $redis->rpush('people', $nama);
        return [1, $nama . ' has been right pushed'];
    } else {
        return [0, "List already has 10 data"];
    }
}
function lpop()
{
    global $redis;

    $lpop = $redis->lpop('people');
    return [1, $lpop . ' has been left popped'];
    
    
}
function rpop()
{

    global $redis;
    $rpop = $redis->rpop('people');
    return [1, $rpop . ' has been right popped'];
}



if (isset($_POST['action'])) {
    $result = array(
        'status' => 0,
        'message' => ""
    );

    $res = '';
    $action = $_POST['action'];
    if (function_exists($action)) {
        if ($action === 'lpush') {
            if (isset($_POST['nama'])) {
                $value = $_POST['nama'];
                if (!empty($value)) {
                    $res = lpush($value);
                    $result['status'] = $res[0];
                    $result['message'] = $res[1];
                }
                else{
                    $result['status'] = 0;
                    $result['message'] = 'String is Empty';
                }
            }
        } elseif ($action === 'lpop') {
            $res = lpop();
            $result['status'] = $res[0];
            $result['message'] = $res[1];
        } elseif ($action === 'rpush') {
            if (isset($_POST['nama'])) {
                $value = $_POST['nama'];
                if (!empty($value)) {
                    $res = rpush($value);
                    $result['status'] = $res[0];
                    $result['message'] = $res[1];
                }
                else{
                    $result['status'] = 0;
                    $result['message'] = 'String is Empty';
                }
            }
        } elseif ($action  === 'rpop') {
            $res = rpop();
            $result['status'] = $res[0];
            $result['message'] = $res[1];
        }
    } else {
        $result['status'] = 0;
            $result['message'] = 'Action Not Found';
    }
    echo json_encode($result);
    // echo $message;
}
