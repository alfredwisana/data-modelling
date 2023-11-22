<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redis Time Series</title>

    <!-- bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">

    <title>Redis</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
    #table_wrapper {
        margin: 0 auto;
        position: relative;


    }

    #wrapper {
        margin: 0 auto;
        position: relative;
        width: 75%;
    }

    /* #upload {} */

    table,
    th,
    td {
        border: 1px solid black;
    }

    .table {
        margin: 0 auto;
        width: 100%;
        margin-right: 10rem;
    }

    #table_title {
        font-weight: bold;
        text-align: center;
        font-size: larger;
        background-color: lightgray;
    }
</style>

<body>
    <div id=wrapper>
        <div id="upload">

            <label for="file">Choose a CSV file:</label>
            <br>
            <input type="file" name="file" id="file" accept=".csv" required>
            <button type="submit" id="butt_upload">Upload</button>

        </div>
        <br>
        <div id="data">

            <button type="submit" id="raw_butt">RAW</button>
            <button type="submit" id="agr_butt">AGR</button>

        </div>
        <br>
        <div id="tab_wrapper">
            <table class="table">
                <table class="table">
                    <h3 id="table_title">GLOBAL LAND TEMPERATURE</h3>
                    <h4 id="status"></h4>
                    <div id="csvtable"></div>

                    <tbody>

                    </tbody>
                </table>
        </div>
    </div>
</body>

</html>


<script>
    $(document).ready(function() {
        $('#butt_upload').on('click', function() {
            var fileInput = $('#file')[0].files[0];

            if (fileInput) {
                var formData = new FormData();
                formData.append('file', fileInput);

                // Send the file to the server using jQuery AJAX
                $.ajax({
                    type: 'POST',
                    url: 'upload.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $("#status").html("CSV DATA");
                        $("#csvtable").html(result);
                        console.log(result);

                    },
                    error: function() {
                        console.error('Error uploading file.');
                    }
                });
            } else {
                console.error('No file selected.');
            }
        });

        $('#raw_butt').on('click', function() {
            var v_functname = "showraw";

            $.ajax({
                type: 'POST',
                url: 'showdata.php',
                data: {
                    functname: v_functname
                },
                dataType: 'html', // Specify the expected data type
                success: function(result) {
                    $("#status").html("RAW DATA");
                    $("#csvtable").html(result);

                    console.log(result);
                }
            });
        });

        $('#agr_butt').on('click', function() {
            var v_functname = "aggregate";

            $.ajax({
                type: 'POST',
                url: 'showdata.php',
                data: {
                    functname: v_functname
                },
                dataType: 'html', // Specify the expected data type
                success: function(result) {
                    $("#status").html("AGGREGATED DATA");
                    $("#csvtable").html(result);
                    console.log(result);
                }
            });
        });
    });
</script>