<?php
require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port'  => 6379
]);

function add($nama)
{
    global $redis;
    $len = $redis->scard('person');
    if ($len < 10) {
        if ($redis->sismember('person', $nama))
        {
            return [0, $nama . ' already in the set'];
        }
        else{
            $redis->sadd('person', $nama);
            return [1, $nama . ' has been added'];
            
        }
         
        
    } else {

        return [0, "Set already has 10 data"];
    }
}

function delete($nama)
{
    global $redis;
    if ($redis->sismember('person', $nama))
    {
        $redis->srem('person', $nama);
        return [1, $nama . ' has been deleted'];
    }else{
        return [0, $nama . ' not in set'];
    }
    
}

if (isset($_POST['action'])) {
    $result = array(
        'status' => 0,
        'message' => ""
    );

    $res = '';
    $action = $_POST['action'];
    if (function_exists($action)) {
        if ($action === 'add') {
            if (isset($_POST['nama'])) {
                $value = $_POST['nama'];
                if (!empty($value)) {
                    $res = add($value);
                    $result['status'] = $res[0];
                    $result['message'] = $res[1];
                }
                else{
                    $result['status'] = 0;
                    $result['message'] = 'String is Empty';
                }
            }else{
                $result['status'] = 0;
                $result['message'] = 'String is Empty';
            }
        } 
        elseif ($action === 'delete') {
            $value = $_POST['nama'];
            $res = delete($value);
            $result['status'] = $res[0];
            $result['message'] = $res[1];
        }  
    } else {
        $result['status'] = 0;
            $result['message'] = 'Action Not Found';
    }
    
    // echo $message;
}else{
    $result = array(
        'status' => 0,
        'message' => "Gagal Masuk"
    );
}

echo json_encode($result);
?>