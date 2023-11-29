<?php

require_once 'autoload.php';

use MongoDB\BSON\Regex;


$client = new MongoDB\Client();

$resto = $client->dmds->resto;


if (isset($_POST['borough']) || isset($_POST['cuisine']) || isset($_POST['score']) || isset($_POST['regex'])) {

    $borough = $_POST['borough'];
    $cuisine = $_POST['cuisine'];
    $score = $_POST['score'];
    if (isset($_POST['regex'])) {
        $re = '$' . $_POST['regex'];
    }
    if ($borough != 0 && !empty($cuisine) && !empty($score) && isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'borough' => $borough,
                'cuisine' => ['$regex' => new Regex($cuisine, "i")],
                'grades.score' => [$re => $score],
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );

        if($cursor->count() == 0){
            echo '<p> No Data Found </p>';
        };
        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } elseif ($borough != 0 && empty($cuisine) && empty($score) && !isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'borough' => $borough,

            ],
            [
                'projection' => ['_id' => 0]
            ]
        );


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } elseif ($borough != 0 && !empty($cuisine) && empty($score) && !isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'borough' => $borough,
                'cuisine' => ['$regex' => new Regex($cuisine, "i")],
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } elseif ($borough != 0 && empty($cuisine) && !empty($score) && isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'borough' => $borough,
                'grades.score' => [$re => $score],
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } elseif ($borough === '0' && !empty($cuisine) && empty($score) && !isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'cuisine' => ['$regex' => new Regex($cuisine, "i")],
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } elseif ($borough === '0' && !empty($cuisine) && !empty($score) && isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'cuisine' => ['$regex' => new Regex($cuisine, "i")],
                'grades.score' => [$re => $score],
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } elseif ($borough === '0' && empty($cuisine) && !empty($score) && isset($_POST['regex'])) {

        $cursor = $resto->find(
            [
                'grades.score' => [$re => $score],
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    } else {
        $cursor = $resto->find();


        foreach ($cursor as $doc) {
            echo '<br>';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="card-body">';

            foreach ($doc as $key => $value) {
                if (is_string($value)) {
                    echo '<p class="card-text">' . $key . ':' .  $value . '</p>';
                }
                if ($key === 'grades') {
                    echo '<p class="card-text">' . $key . ":";
                    foreach ($value as $grade) {

                        echo '<p class="card-text">Date: ' . $grade['date'] . '</p>';
                        echo '<p class="card-text">Grade: ' . $grade['grade'] . '</p>';
                        echo '<p class="card-text">Score: ' . $grade['score'] . '</p>';;
                    }
                }
            }
            echo '</div>';
            echo '</div>';
        }
    };
} else {
    echo "no data";
}
?>