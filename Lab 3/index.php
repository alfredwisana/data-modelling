<!-- Alfred Wisana
c14210177 -->
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
    <title>Mongo DB</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
        <button type="submit" id="butt-filter"></button>
        
        </div>
        <div id="data">
        
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function(){
        $('#raw_butt').on('click', function() {
            var v_borough=a;
            var v_cuisine=b;
            var v_score = 0;
            var v_regex = 0;

            $.ajax({
                type:'POST',
                url: "process.php",
                data:{

                },
                success:function(result){
                    $("#data").html(result);
                }
            })
        }
    })
</script>