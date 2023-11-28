<?php


require_once 'autoload.php';

$client = new MongoDB\Client();

$resto = $client->dmds->resto;


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<style>
    #wrapper {
        margin: 0 auto;
        width: 50%;
    }
</style>

<body>
    <div id="wrapper">
        <div id="filter">
            
        <input type="text" name="" id="cuisine">
        <input type="number" name="" id="score">
        
        </div>
    </div>
</body>

</html>