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

    #filter {
        margin-top: 1.5rem;
        background-color: lightgoldenrodyellow;
        padding-top: 1.5px;
        height: 2.75rem;
        padding-left: 1.7rem;
    }

    #data {
        width: 100%;
        padding-left: 17.5rem;
        padding-top: 1.5rem;
        padding-bottom: 0.5rem;
        background-color: lightcyan;
    }

    .card {
        position: relative;
        background-color: lightblue;
    }
</style>

<body>
    <div id="wrapper">
        <div id="filter">
            <select  id="borough">
                <option value="0">Choose Borough</option>
                <?php
                $cursor = $resto->distinct('borough');
                foreach ($cursor as $str) {

                ?>
                    <option value="<?php echo $str; ?>"><?php echo $str; ?></option>
                <?php
                }
                ?>
            </select>
            <input type="text" name="" id="cuisine" placeholder="Cuisine">
            <input type="number" name="" id="score" placeholder="Score">
            <!-- <input type="radio" name="regex" value="gt"> GT
            <input type="radio" name="regex" value="gte"> GTE
            <input type="radio" name="regex" value="eq"> EQ
            <input type="radio" name="regex" value="lte"> LTE
            <input type="radio" name="regex" value="lt"> LT -->
            <button type="submit" id="butt-filter" class="btn btn-outline-dark">Filter</button>

            <!-- <button type="submit" id="butt-filter">Filter</button> -->

        </div>
        <br>
        <div id="data">
            <?php
            $cursor = $resto->find();
            foreach ($cursor as $doc) {
            ?>
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <?php
                        foreach ($doc as $key => $value) {
                            if (is_string($value)) { ?>

                                <p class="card-text"><?php echo $key . ": " . $value; ?></p>


                            <?php
                            }
                            if ($key === 'grades') {
                            ?>
                                <p class="card-text"><?php echo $key . ":" ?>
                                    <?php
                                    foreach ($value as $grade) {
                                    ?>
                                    <p class="card-text">Date: <?php echo  $grade['date']; ?></p>
                                    <p class="card-text">Grade: <?php echo $grade['grade']; ?></p>
                                    <p class="card-text">Score: <?php echo $grade['score']; ?></p>

                                    <?php
                                    }
                                    ?>

                                </p>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <br>
            <?php
            }

            ?>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
                $('#butt-filter').on('click', function() {
                        var v_borough = $("#borough").val();
                        var v_cuisine = $("#cuisine").val();
                        var v_score = $("#score").val();
                       // var v_regex = $("input[name='regex']:checked").val();

                        console.log(v_borough);
                        console.log(v_cuisine);
                        console.log(v_score);
                        // console.log(v_regex);
                        $.ajax({
                            type: 'POST',
                            url: "process.php",
                            data: {
                                borough: v_borough,
                                cuisine: v_cuisine,
                                score: v_score,
                                //regex: v_regex
                            },
                            success: function(result) {
                                $("#data").html(result);
                                console.log(result);
                            }
                        })
                    })
                });
</script>