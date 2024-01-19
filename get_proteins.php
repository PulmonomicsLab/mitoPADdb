<?php
    include('db.php');

    $concise = false;
    if (array_key_exists("concise", $_GET))
        if ($_GET["concise"] == "1")
            $concise = true;
    $key = urldecode($_GET['key']);
//     echo $key."<br/>";
    $value = $key."%";

    $conn = connect();

    $proteinAttributes = array(
        "UniProtAccession"=>"UniProt accession ID",
        "GeneName"=>"Gene name",
    );

    $proteinQuery = "select ".implode(",", array_keys($proteinAttributes))." from protein where GeneName like ? order by GeneName;";
//     echo $proteinQuery."<br/>";
    $proteinStmt = $conn->prepare($proteinQuery);
    $proteinStmt->bind_param("s", $value);
    $proteinStmt->execute();
    $proteinRows = execute_and_fetch_assoc($proteinStmt);
    $proteinStmt->close();
    if (! $concise) {
        for($i=0; $i<count($proteinRows); ++$i){
            $diseaseQuery = "select disease.MADID,DiseaseName from disease inner join protein_disease_association on disease.MADID=protein_disease_association.MADID where UniProtAccession=?;";
//             echo $diseaseQuery."<br/>";
            $diseaseStmt = $conn->prepare($diseaseQuery);
            $diseaseStmt->bind_param("s", $proteinRows[$i]["UniProtAccession"]);
            $diseaseStmt->execute();
            $diseaseRows = execute_and_fetch_assoc($diseaseStmt);
            $diseases = array();
            for ($j=0; $j<count($diseaseRows); ++$j)
                array_push($diseases, $diseaseRows[$j]["DiseaseName"]." (<a href=\"disease.php?key=".$diseaseRows[$j]["MADID"]."\">".$diseaseRows[$j]["MADID"]."</a>)");
            $proteinRows[$i]["DiseaseAssociations"] = implode("; ", $diseases);
        }
    }

    closeConnection($conn);
    $rowsJSON = json_encode($proteinRows);
    echo $rowsJSON;
?>
