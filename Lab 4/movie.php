<?php

require_once 'autoload.php';

$client = Laudis\Neo4j\ClientBuilder::create()->withDriver('default', 'bolt://neo4j:c14210177@localhost')->build();

$result = $client->run(<<<'CYPHER'
MATCH (people:Person) RETURN people
CYPHER, ['dbName' => 'neo4j']);

// var_dump($result);

// $neo4j = $result->get('neo4j');
// $rating = $result->get('rating');

// // Outputs "neo4j is 10 out of 10"
// echo $neo4j->getProperty('name').' is '.$rating->getProperty('value') . ' out of 10!';

foreach($result as $person){
    echo $person -> get('people')-> getProperty('name') . "</br>\n";
}

echo "</br>";
$result = $client->run(<<<'CYPHER'
MATCH (people:Person) RETURN people.name
CYPHER, ['dbName' => 'neo4j']);

// var_dump($result);

// $neo4j = $result->get('neo4j');
// $rating = $result->get('rating');

// // Outputs "neo4j is 10 out of 10"
// echo $neo4j->getProperty('name').' is '.$rating->getProperty('value') . ' out of 10!';

foreach($result as $person){
    echo $person -> get('people.name') . "</br>\n";
}


echo "</br>";
$result = $client->run(<<<'CYPHER'
MATCH (tom:Person {name:"Tom Hanks"})-[:ACTED_IN]->(m)<-[:ACTED_IN]-(coActors),
    (coActors)-[:ACTED_IN]->(m2)<-[:ACTED_IN]-(cocoActors)
  WHERE NOT (tom)-[:ACTED_IN]->()<-[:ACTED_IN]-(cocoActors) AND tom <> cocoActors
  RETURN cocoActors.name AS Recommended, count(*) AS Strength ORDER BY Strength DESC
CYPHER, ['dbName' => 'neo4j']);

// var_dump($result);

// $neo4j = $result->get('neo4j');
// $rating = $result->get('rating');

// // Outputs "neo4j is 10 out of 10"
// echo $neo4j->getProperty('name').' is '.$rating->getProperty('value') . ' out of 10!';

foreach($result as $row){
    echo $row -> get('Recommended') ."|". $row->get('Strength') ."</br>\n";
}
?>
