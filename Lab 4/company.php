<?php

require_once 'autoload.php';

if (isset($_POST['company'])) {
    $client = Laudis\Neo4j\ClientBuilder::create()->withDriver('default', 'bolt://neo4j:c14210177@localhost')->build();

    $companyName = $_POST['company'];
    if ($companyName !== "All") {
        $result = $client->run(<<<CYPHER
    MATCH (s1:Supplier)-->()-->()<--()<--(s2:Supplier)
    WHERE s1.companyName = "$companyName" AND id(s1) <> id(s2)
    RETURN s2.companyName as Competitor, count(s2) as NoProducts
    ORDER BY NoProducts DESC
    CYPHER, ['dbName' => 'neo4j']);
    } else {
        $result = $client->run(<<<CYPHER
        MATCH (s1:Supplier)-->()-->()<--()<--(s2:Supplier)
        RETURN s2.companyName as Competitor, count(s2) as NoProducts
        ORDER BY NoProducts DESC
        CYPHER, ['dbName' => 'neo4j']);
    }

    foreach ($result as $company) {
        echo "<tr>";
        echo '<th>' . $company->get('Competitor') . "</th>";
        echo '<th>' . $company->get('NoProducts') . "</th>";
        echo "</tr>";
    }
}
