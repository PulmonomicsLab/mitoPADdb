<?php
    include('db.php');

    $conn = connect();

    $pathwayQuery = "select distinct(Pathway) from mitocarta_pathway order by Pathway;";
//     echo $pathwayQuery."<br/>";
    $pathwayStmt = $conn->prepare($pathwayQuery);
    $pathwayStmt->execute();
    $pathwayRows = execute_and_fetch_assoc($pathwayStmt);
    $pathwayStmt->close();

    $pathwayNodeMap = array();
    $pathwayTree = new stdClass();
    $rootObject = new stdClass();
    $rootObject->text = "All Pathways";
    $rootObject->children = array();
    $rootObject->state = new stdClass();
    $rootObject->state->opened = true;
    $pathwayTree->data = array();

    for ($i=0; $i<count($pathwayRows); ++$i){
        $pathwayNodeMap[$pathwayRows[$i]["Pathway"]] = "pathway_node".strval($i);
        $pathway_node = new stdClass();
        $pathway_node->text = "Pathway - ".$pathwayRows[$i]["Pathway"];
//         $pathway_node->id = "pathway_node_".strval($i);
//         $pathway_node->parent = "#";
//         $pathway_node->state = new stdClass();
//         $pathway_node->state->opened = false;
//         $pathway_node->state->selected = false;

        $pathway_node->children = array();

        $proteinQuery = "select mitocarta_pathway.UniProtAccession, GeneName from mitocarta_pathway inner join protein on mitocarta_pathway.UniProtAccession=protein.UniProtAccession where mitocarta_pathway.Pathway='".$pathwayRows[$i]["Pathway"]."' order by mitocarta_pathway.Pathway;";
//         echo $pathwayQuery."<br/>";
        $proteinStmt = $conn->prepare($proteinQuery);
        $proteinStmt->execute();
        $proteinRows = execute_and_fetch_assoc($proteinStmt);
        $proteinStmt->close();
//         echo count($proteinRows);
        for ($j=0; $j<count($proteinRows); ++$j) {
            $protein_node = new stdClass();
            $protein_node->text = "Protein - ".$proteinRows[$j]["GeneName"]." (".$proteinRows[$j]["UniProtAccession"].")";
//             $protein_node->id = "protein_node_".strval($j)."_pathway_".strval($i);
//             $protein_node->state = new stdClass();
//             $protein_node->state->opened = false;
//             $protein_node->state->selected = false;

            $protein_node->children = array();

            $diseaseQuery = "select disease.MADID,DiseaseName from disease inner join protein_disease_association on disease.MADID=protein_disease_association.MADID where UniProtAccession='".$proteinRows[$j]["UniProtAccession"]."';";
//             echo $pathwayQuery."<br/>";
            $diseaseStmt = $conn->prepare($diseaseQuery);
            $diseaseStmt->execute();
            $diseaseRows = execute_and_fetch_assoc($diseaseStmt);
            $diseaseStmt->close();
            for ($k=0; $k<count($diseaseRows); ++$k) {
                $disease_node = new stdClass();
                $disease_node->text = "Disease - ".$diseaseRows[$k]["DiseaseName"]." (".$diseaseRows[$k]["MADID"].")";
//                 $disease_node->id = "disease_node_".strval($k)."_protein_".strval($j)."_pathway_".strval($i);
//                 $disease_node->state = new stdClass();
//                 $disease_node->state->opened = false;
//                 $disease_node->state->selected = false;
                array_push($protein_node->children, $disease_node);
            }

            array_push($pathway_node->children, $protein_node);
        }

        array_push($rootObject->children, $pathway_node);
    }

    array_push($pathwayTree->data, $rootObject);

    $json = json_encode($pathwayTree);

    closeConnection($conn);

    echo $json;

?>

