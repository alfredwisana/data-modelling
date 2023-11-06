<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">

        <title>Redis</title>
        <link rel="icon" type="image/png" href="./images/logo2.png" sizes="16x16">
    </head>

    <style>
        .table {
            margin: 0 auto;
            position: relative;
            width: 50%;
            margin-top: 5rem;
            text-align: center
        }

        ;
    </style>
</head>

<body>
    <?php
    require 'Predis/Predis/Autoload.php';

    use Predis\Client;

    $redis = new Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port'  => 6379
    ]);

    ?>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">PEOPLE</th>
            </tr>
        </thead>
        <tbody>
            <?php

            ?>

            <tr>
                <td><input type="text" id="nama">
                    <br><br>
                    <ul class="horizontal_listy">
                        <span><button id="lpush">LPUSH</button></span>
                        <span><button id="lpop">LPOP</button></span>
                        <span><button id="rpush">RPUSH</button></span>
                        <span><button id="rpop">RPOP</button></span>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</body>


<script>
    $(document).ready(function() {
        v_nama = document.getElementById("nama")
        $("#lpush").click(function() {
            callFunction("lpush");
        });

        $("#lpop").click(function() {
            callFunction("lpop");
        });
        $("#rpush").click(function() {
            callFunction("rpush");
        });
        $("#lpop").click(function() {
            callFunction("rpop");
        });

        function callFunction(functionName) {
            $.ajax({
                type: "GET",
                url: "index.php",
                data: {
                    action: functionName,
                    nama: v_nama
                },
                success: function(data) {}
            });
        }
    });
</script>

</html>


<?php
function lpush($nama)
{
    $redis->lpush('people', '$nama');
}

function rpush($nama)
{
    $redis->rpush('people', '$nama');
}
function lpop($nama)
{
    $redis->lpop('people');
}
function rpop($nama)
{
    $redis->rpop('people');
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if (function_exists($action)) {
        if ($action === 'lpush') {
            $value = $_GET['nama'];
            lpush($value);
            
        } elseif ($action === 'lpop') {
            lpop();
        }elseif ($action === 'rpush'){
            $value = $_GET['nama'];
            rpush($value);
        }elseif($action  === 'rpop'){
            rpop();
        }
    } else {
        echo '<script type="text/javascript">alert("No Function Specified");</script>';
    }
} else {
    echo '<script type="text/javascript">alert("No Function Specified");</script>';
}
?>