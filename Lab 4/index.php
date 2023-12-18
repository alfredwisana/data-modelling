<?php

require_once 'autoload.php';

$client = Laudis\Neo4j\ClientBuilder::create()->withDriver('default', 'bolt://neo4j:c14210177@localhost')->build();


$result = $client->run(<<<'CYPHER'
MATCH (s1:Supplier)-->()-->()<--()<--(s2:Supplier)
WHERE s1.companyName = «company» AND s1 <> s2
RETURN s2.companyName as Competitor, count(s2) as NoProducts
ORDER BY NoProducts DESC
CYPHER, ['dbName' => 'neo4j']);


foreach($result as $person){
    echo $person -> get('Competitor'). "</br>\n";
}

?>