<!-- Alfred Wisana
C14210177 -->
<?php

require_once 'autoload.php';

$client = Laudis\Neo4j\ClientBuilder::create()->withDriver('default', 'bolt://neo4j:c14210177@localhost')->build();

$delete = $client->run('MATCH (n) DETACH DELETE n');

$product = $client->run('LOAD CSV WITH HEADERS FROM "https://data.neo4j.com/northwind/products.csv" AS row
CREATE (n:Product)
SET n = row,
    n.unitPrice = toFloat(row.unitPrice),
    n.unitsInStock = toInteger(row.unitsInStock),
    n.unitsOnOrder = toInteger(row.unitsOnOrder),
    n.reorderLevel = toInteger(row.reorderLevel),
    n.discontinued = (row.discontinued <> "0")');

$category = $client->run('LOAD CSV WITH HEADERS FROM "https://data.neo4j.com/northwind/categories.csv" AS row
CREATE (n:Category)
SET n = row');


$supplier = $client->run('LOAD CSV WITH HEADERS FROM "https://data.neo4j.com/northwind/suppliers.csv" AS row
CREATE (n:Supplier)
SET n = row');


// Relationship

$partOf = $client->run(<<<'CYPHER'
MATCH (p:Product),(c:Category)
WHERE p.categoryID = c.categoryID
CREATE (p)-[:PART_OF]->(c) 
CYPHER, ['dbName' => 'neo4j']);

$supplies = $client->run(<<<'CYPHER'
MATCH (p:Product),(s:Supplier)
WHERE p.supplierID = s.supplierID
CREATE (s)-[:SUPPLIES]->(p)
CYPHER, ['dbName' => 'neo4j']);

$result = $client->run(<<<CYPHER
        MATCH (company:Supplier) RETURN company.companyName
        ORDER BY company.companyName ASC
        CYPHER, ['dbName' => 'neo4j']);
$res = $client->run(<<<CYPHER
    MATCH (s1:Supplier)-->()-->()<--()<--(s2:Supplier)
    
    RETURN s2.companyName as Competitor, count(s2) as NoProducts
    ORDER BY NoProducts DESC
    CYPHER, ['dbName' => 'neo4j']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Neo4J</title>
</head>
<style>
    #wrapper {
        margin: 0 auto;
        width: 50%;
    }

    #filter {
        margin-top: 1.5rem;
        background-color: lightgoldenrodyellow;
        padding-top: 1.75px;
        height: 2.75rem;
        padding-left: 1.7rem;
    }


    table,
    th,
    td {
        border: 1px solid black;
        text-align: left;
    }

    .table {
        margin: 0 auto;
        width: 100%;
        margin-right: 10rem;
    }

    #table_title,
    #status {
        font-weight: bold;
        text-align: center;
        font-size: larger;
        background-color: lightgray;
    }

    #coltitle {
        font-weight: bold;
        background-color: lightgoldenrodyellow;
    }
</style>

<body>
    <div id="wrapper">
        <div id="filter">
            <label for="companyName">Company Name</label>
            <select id="companyName">
                <option value="All">Select A Company</option>
                <?php
                foreach ($result as $company) {
                ?>
                    <option value="<?php echo $company->get('company.companyName'); ?>"><?php echo $company->get('company.companyName'); ?></option>
                <?php
                }
                ?>
            </select>

        </div>

        <div id="data">
            <h4 id="company">All</h4>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Competitor</th>
                        <th scope="col">No Products</th>
                    </tr>
                </thead>
                <tbody id="companyData">
                    <?php
                    foreach ($res as $company) {
                        echo "<tr>";
                        echo '<th>' . $company->get('Competitor') . "</th>";
                        echo '<th>' . $company->get('NoProducts') . "</th>";
                        echo "</tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>


<script>
    $(document).ready(function() {
        $('#companyName').on('change', function() {
            var v_company = $("#companyName").val();

            $.ajax({
                type: 'POST',
                url: "company.php",
                data: {
                    company: v_company

                },
                success: function(result) {
                    $("#company").html(v_company);
                    $("#companyData").html(result);
                    console.log(result);
                }
            })
        })
    });
</script>