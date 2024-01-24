<!-- Alfred Wisana
C14210177 -->
<?php

require_once 'autoload.php';

$client = Laudis\Neo4j\ClientBuilder::create()->withDriver('default', 'bolt://neo4j:c14210177@localhost')->build();


$result = $client->run(<<<'CYPHER'
MATCH (people:Person) RETURN people
CYPHER, ['dbName' => 'neo4j']);




?>