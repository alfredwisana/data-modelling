<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">

    <title>Redis</title>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>



    <style>
        .table {
            margin: 0 auto;
            position: relative;
            width: 25%;
            margin-top: 2rem;
            text-align: center;
            border: 1px solid black;
        }

        h1 {
            margin-top: 5rem;

            text-align: center;
            font-weight: bold;
        }

        input {
            width: 50%;
        }

        ;
    </style>
</head>

<body>


    <table class="table">
        <thead>
            <h1>PEOPLE</h1>
        </thead>

        <tbody>
            <?php
            require 'Predis/Predis/Autoload.php';

            use Predis\Client;

            $redis = new Client([
                'scheme' => 'tcp',
                'host' => '127.0.0.1',
                'port'  => 6379
            ]);

            $peopleName = $redis->lrange('people', 0, -1);
            foreach ($peopleName as $name) {
                echo "<tr>
                <td>$name</td>
                </tr>";
            };


            ?>

            <tr>
                <td><input type="text" id="nama" class="form-control" placeholder="input name here">
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


        function callFunction(functionName) {
            v_nama = $("#nama").val();
            $.ajax({
                type: "GET",
                url: "index.php",
                data: {
                    action: functionName,
                    nama: v_nama
                },
                success: function(result) {
                    alert(result);
                    window.location.reload();
                }
            });
        }

        $("#lpush").click(function() {
            callFunction("lpush");
        });

        $("#lpop").click(function() {
            callFunction("lpop");
        });
        $("#rpush").click(function() {
            callFunction("rpush");
        });
        $("#rpop").click(function() {
            callFunction("rpop");
        });


    });
</script>

</html>


<?php


function lpush($nama)
{
    global $redis;
    $len = $redis->llen('people');
    if ($len < 10) {
        $redis->lpush('people', $nama);
    } else {
        echo 'List already has 10 data';
    }
}

function rpush($nama)
{
    global $redis;
    $len = $redis->llen('people');
    if ($len < 10) {
        $redis->rpush('people', $nama);
    } else {
        echo 'List already has 10 data';
    }
}
function lpop()
{
    global $redis;

    $redis->lpop('people');
}
function rpop()
{
    global $redis;
    $redis->rpop('people');
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if (function_exists($action)) {
        if ($action === 'lpush') {
            if (isset($_GET['nama'])) {
                $value = $_GET['nama'];
                if (!empty($value)) {
                    lpush($value);
                }
            }
        } elseif ($action === 'lpop') {
            lpop();
        } elseif ($action === 'rpush') {
            if (isset($_GET['nama'])) {
                $value = $_GET['nama'];
                if (!empty($value)) {
                    rpush($value);
                }
            }
        } elseif ($action  === 'rpop') {
            rpop();
        }
    } else {
        echo '<script type="text/javascript">alert("No Function Specified");</script>';
    }
}
?>