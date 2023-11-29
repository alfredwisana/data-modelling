<?php

require_once 'autoload.php';

// using localhost
$client = new MongoDB\Client();
//$client = new MongoDB\Client('mongodb://192.168.38.200:27017');
// client -> db -> collection
$resto = $client->dmds->resto;

// Get distinct boroughs in dataset
$cursor = $resto->distinct('borough');
echo "BOROUGH:\n";
foreach ($cursor as $str) {
  echo "\t$str\n";
}

echo "<br><br>\n";

// Find all restaurants in Staten Island
$cursor = $resto->find(
  [
    'borough' => 'Staten Island',
  ],
  [
    'projection' => [
      '_id' => 0,
    ],
  ]
);
foreach ($cursor as $doc) {
  echo "{\n";
  foreach ($doc as $key => $value) {
    if (is_string($value)) {
      echo "\t$key: $value\n";
    }
/*
    else if (is_object($value) || is_array($value)) {
      echo "  $key => ";
      var_dump($value);
      echo "\n";
    }
*/
  }
  echo "}\n";
}

echo "<br><br>\n";

// Find all restaurants receiving grades.score > 70
$cursor = $resto->find(
  [
    'grades.score' => ['$lt' => 5,],
  ],
  [
    'projection' => [
      '_id' => 0,
    ],
  ]
);
foreach ($cursor as $doc) {
  echo "{\n";
  foreach ($doc as $key => $value) {
    if (is_string($value)) {
      echo "\t$key: $value\n";
    }
    if ($key === "grades") {
      echo "\t$key => \n";
      foreach ($value as $grade) {
        echo "\t\t{\n";
        echo "\t\t\tdate: {$grade['date']}\n";
        echo "\t\t\tgrade: {$grade['grade']}\n";
        echo "\t\t\tscore: {$grade['score']}\n";
        echo "\t\t}\n";
      }
    }
  }
  echo "}\n";
}

?>