<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">

    <title>Redis</title>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<?php
require 'Predis/Predis/Autoload.php';

use Predis\Client;

$redis = new Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port'  => 6379
]);
?>
<style>
    #wrapper {
        margin: 0 auto;
        position: relative;
        width: 50%;
    }

    /* #upload {} */

    .table {
        border: 1px black solid;
    }

    #caption {
        font-weight: bold;
        text-align: center;
        font-size: larger;
        background-color: lightgray;
    }
</style>

<body>
    <div id="wrapper">
        <div id="upload">
            <form action="index.php" method="post">
                <label for="file">Choose a CSV file:</label>
                <br>
                <input type="file" name="file" id="file" accept=".csv" required>
                <button type="submit">Upload</button>
            </form>
        </div>
        <br>
        <div id="data">
            <form action="index.php" method="post">
                <button type="submit">RAW</button>
                <button type="submit">AGR</button>
            </form>
        </div>
        <br>
        <div id="tabel">
            <table class="table">
                <caption id="caption">GLOBAL LAND TEMPERATURE</caption>
                <thead>
                    <th></th>
                </thead>
            </table>
        </div>
    </div>




    <table>

    </table>
</body>

</html>

