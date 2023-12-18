<?php
require_once 'autoload.php';

$product = $client->run(<<<'CYPHER'
LOAD CSV WITH HEADERS FROM "https://data.neo4j.com/northwind/products.csv" AS row
CREATE (n:Product)
SET n = row,
n.unitPrice = toFloat(row.unitPrice),
n.unitsInStock = toInteger(row.unitsInStock), n.unitsOnOrder = toInteger(row.unitsOnOrder),
n.reorderLevel = toInteger(row.reorderLevel), n.discontinued = (row.discontinued <> "0")
    CYPHER, ['dbName' => 'neo4j']);

$client = Laudis\Neo4j\ClientBuilder::create()->withDriver('default', 'bolt://neo4j:c14210177@localhost')->build();

$companyName = "Exotic Liquids";

$result = $client->run(<<<CYPHER
    MATCH (s1:Supplier)-->()-->()<--()<--(s2:Supplier)
    
    RETURN s2.companyName as Competitor, count(s2) as NoProducts
    ORDER BY NoProducts DESC
    CYPHER, ['dbName' => 'neo4j']);


foreach ($result as $company) {

    echo  $company->get('Competitor') . " " . $company->get('NoProducts');
    echo "<br>";
    // echo $company->get('company.NoProducts') ;

}
