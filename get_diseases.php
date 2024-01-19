<?php
    include('db.php');

    $concise = false;
    if (array_key_exists("concise", $_GET))
        if ($_GET["concise"] == "1")
            $concise = true;
    $key = urldecode($_GET['key']);
//     echo $key."<br/>";

    $conn = connect();

    $diseaseAttributes = array(
        "MADID"=>"Disease ID",
        "DiseaseName"=>"Disease name",
        "DiseaseCategory"=>"Disease category",
    );

    $diseaseQuery = "select ".implode(",", array_keys($diseaseAttributes))." from disease where DiseaseCategory=? order by DiseaseName;";
//     echo $diseaseQuery."<br/>";
    $diseaseStmt = $conn->prepare($diseaseQuery);
    $diseaseStmt->bind_param("s", $key);
    $diseaseStmt->execute();
    $diseaseRows = execute_and_fetch_assoc($diseaseStmt);
    $diseaseStmt->close();
    if (! $concise) {
        for($i=0; $i<count($diseaseRows); ++$i){
            $proteinQuery = "select protein.UniProtAccession,GeneName from protein inner join protein_disease_association on protein.UniProtAccession=protein_disease_association.UniProtAccession where MADID=?;";
//             echo $proteinQuery."<br/>";
            $proteinStmt = $conn->prepare($proteinQuery);
            $proteinStmt->bind_param("s", $diseaseRows[$i]["MADID"]);
            $proteinStmt->execute();
            $proteinRows = execute_and_fetch_assoc($proteinStmt);
            $proteins = array();
            for ($j=0; $j<count($proteinRows); ++$j)
                array_push($proteins, $proteinRows[$j]["GeneName"]." (<a href=\"protein.php?keytype=ID&key=".$proteinRows[$j]["UniProtAccession"]."\">".$proteinRows[$j]["UniProtAccession"]."</a>)");
            $diseaseRows[$i]["ProteinAssociations"] = implode("; ", $proteins);
        }
    }

    closeConnection($conn);
    $rowsJSON = json_encode($diseaseRows);
    echo $rowsJSON;
?>
