<?php
    include('db.php');

    function get_PubMed_links($text){
        $img_tag = "<img src=\"resource/redirect-icon.png\" height=\"12px\" width=\"auto\" />";
        $ids = explode("|", explode(":", $text)[1]);
        $links = array();
        foreach($ids as $id)
            array_push($links, "<a class=\"link\" target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/".$id."\">".$id."</a>");

        return implode("; ", $links)." ".$img_tag;
    }

    function get_SourceID_links($text){
        $img_tag = "<img src=\"resource/redirect-icon.png\" height=\"12px\" width=\"auto\" />";
        list($source, $id_text) = explode(":", $text);
        $ids = explode("|", $id_text);
        $links = array();
        foreach($ids as $id){
            if ($source === "PubMed")
                array_push($links, "<a class=\"link\" target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/".$id."\">".$id."</a>");
            elseif ($source === "ClinVar")
                array_push($links, "<a class=\"link\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/clinvar/".$id."\">".$id."</a>");
            else
                array_push($links, $id);
        }
        return $source.": ".implode("; ", $links)." ".$img_tag;
    }
    
    $keytype = $_GET["keytype"];
    $key = $_GET["key"];
//     echo $keytype." ".$key."<br/>";
    
    $conn = connect();
    
    $uniprot = "";
    if($keytype === 'ID') {
        $uniprot = $key;
    } else {
        $query = "select UniProtAccession from protein where GeneName=?;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $rows = execute_and_fetch_assoc($stmt);
        if(count($rows) > 0)
            $uniprot = $rows[0]["UniProtAccession"];
        $stmt->close();
    }
    
    if ($uniprot !== "") {
        $proteinAttributes = array(
            "UniProtAccession"=>"UniProt accession ID",
            "GeneName"=>"Gene name",
            "GOCC"=>"GO CC",
            "GOMF"=>"GO MF",
            "GOBP"=>"GO BP",
        );
        $proteinQuery = "select ".implode(",", array_keys($proteinAttributes))." from protein where UniProtAccession=?;";
//         echo $proteinQuery."<br/>";
        $proteinStmt = $conn->prepare($proteinQuery);
        $proteinStmt->bind_param("s", $uniprot);
        $proteinStmt->execute();
        $proteinRows = execute_and_fetch_assoc($proteinStmt);
        $proteinStmt->close();
//         echo implode(",", $proteinRows[0])."<br/>";
        
        $diseaseAttributes = array(
            "MADID"=>"Disease ID",
            "DiseaseName"=>"Disease name",
            "DiseaseCategory"=>"Disease category",
            "ProteinDiseaseAssociationID"=>"Association ID"
        );
        $diseaseQuery = "select disease.MADID,DiseaseName,DiseaseCategory,ProteinDiseaseAssociationID from disease inner join protein_disease_association on disease.MADID=protein_disease_association.MADID where UniProtAccession=?;";
//         echo $diseaseQuery."<br/>";
        $diseaseStmt = $conn->prepare($diseaseQuery);
        $diseaseStmt->bind_param("s", $uniprot);
        $diseaseStmt->execute();
        $diseaseRows = execute_and_fetch_assoc($diseaseStmt);
        $diseaseStmt->close();
//         echo count($diseaseRows)."<br/>";
//         foreach($diseaseRows as $row) {
//             echo implode(",", array_keys($row))."<br/>";
//             echo implode(",", $row)."<br/>";
//         }
        
        $snpAttributes = array(
            "MPMutationID"=>"Mutation ID",
            "dbSNPID"=>"dbSNP ID",
            "NucleotideVariation"=>"Nucleotide change",
            "AminoAcidChange"=>"Amino acid change",
            "UniProtAccession"=>"UniProt accession ID",
            "length(MPMutationID)"=>"Gene Name",
            "MADID"=>"Disease ID",
            "SourceID"=>"Source ID",
//             "PMID"=>"PMID",
        );
        $snpQuery = "select ".implode(",", array_keys($snpAttributes))." from nucleotide_variation where UniProtAccession=?;";
    //     echo $snpQuery."<br/>";
        $snpStmt = $conn->prepare($snpQuery);
        $snpStmt->bind_param("s", $uniprot);
        $snpStmt->execute();
        $snpRows = execute_and_fetch_assoc($snpStmt);
        $snpStmt->close();
//         echo count($snpRows)."<br/>";
//         foreach($snpRows as $row) {
//             echo implode(",", array_keys($row))."<br/>";
//             echo implode(",", $row)."<br/>";
//         }
        
        $expAttributes = array(
            "DiseaseProteinExpressionID"=>"Expression ID",
            "ExpressionVariation"=>"Expression variation",
            "ExpressionMolecule"=>"Expression molecule",
            "CellLine"=>"Cell line",
            "Sample"=>"Sample",
            "Method"=>"Method",
            "UniProtAccession"=>"UniProt accession ID",
            "length(DiseaseProteinExpressionID)"=>"Gene Name",
            "MADID"=>"Disease ID",
            "Remarks"=>"Remarks",
            "PMID"=>"PubMed ID",
        );
        $expQuery = "select ".implode(",", array_keys($expAttributes))." from expression where UniProtAccession=?;";
//         echo $expQuery."<br/>";
        $expStmt = $conn->prepare($expQuery);
        $expStmt->bind_param("s", $uniprot);
        $expStmt->execute();
        $expRows = execute_and_fetch_assoc($expStmt);
        $expStmt->close();
//         echo count($expRows)."<br/>";
//         foreach($expRows as $row) {
//             echo implode(",", array_keys($row))."<br/>";
//             echo implode(",", $row)."<br/>";
//         }
    } else {
        $proteinRows = array();
    }
    
    closeConnection($conn);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>mitoPADdb - Mitochondrial Proteins Associated with Diseases database</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
<!--         <script type = "text/javascript" src = "js/advance_search_input.js"></script> -->
    </head>
    <body>
        <div class = "section_header">
            <center><p class="title">mitoPADdb - Mitochondrial Proteins Associated with Diseases database</p></center>
        </div>

        <div class = "section_menu">
            <center>
            <table cellpadding="3px">
                <tr class="nav">
                    <td class="nav"><a href="index.html" class="side_nav">Home</a></td>
                    <td class="nav"><a href="browse.html" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <?php
                if(count($proteinRows) < 1) {
                    if ($keytype === "ID")
                        echo "<center><p>Error !!! No protein exist in the database having UniProt accession ID: ".$uniprot.".</p></center>";
                    else
                        echo "<center><p>Error !!! No protein exist in the database having Gene name: ".$key.".</p></center>";
                } else {
                    echo "<center><h2>UniProt accession ID: ".$uniprot." <a target=\"_blank\" href=\"https://www.uniprot.org/uniprotkb/".$uniprot."\"><img src=\"resource/redirect-icon.png\" height=\"18px\" width=\"auto\" /></a></h2></center>";
                    echo "<table class=\"details\" border=\"1\">";

//                     echo "<tr><th>Attribute</th><th>Value</th></tr>";
//                     foreach ($proteinAttributes as $name=>$fname) {
//                         if ($name !== "UniProtAccession") {
//                             echo "<tr>";
//                             echo "<td style=\"width:25%\"><b>".$fname."</b></td>";
//                             echo "<td style=\"width:75%\">".$proteinRows[0][$name]."</td>";
//                             echo "</tr>";
//                         }
//                     }

                    echo "<tr><td style=\"width:25%\"><b>Gene name</b></td><td style=\"width:75%\">".$proteinRows[0]["GeneName"]."</td>";
                    
                    echo "<tr><td style=\"width:25%\"><b>Disease associations</b></td><td style=\"width:75%\">";
                    $diseases = array();
                    foreach($diseaseRows as $row)
                        array_push($diseases, "<a class=\"link\" href=\"#".$row["MADID"]."\">".$row["DiseaseName"]."</a>");
                    echo implode("; ", $diseases);
                    echo "</td></tr>";
                    
                    echo "<tr><td style=\"width:25%\"><b>Mutations</b></td><td style=\"width:75%\">";
                    $snps = array();
                    foreach($snpRows as $row)
                        array_push($snps, "<a class=\"link\" href=\"#".$row["MPMutationID"]."\">".$row["MPMutationID"]."</a>");
                    echo implode("; ", $snps);
                    echo "</td></tr>";
                    
                    echo "<tr><td style=\"width:25%\"><b>Expression</b></td><td style=\"width:75%\">";
                    $expressions = array();
                    foreach($expRows as $row) {
                        if($row["ExpressionVariation"] === "Down regulation")
                            array_push($expressions, "<a class=\"link\" href=\"#".$row["DiseaseProteinExpressionID"]."\">".$row["DiseaseProteinExpressionID"]."</a><font color=\"red\" title=\"Down regulation\">&darr;</font>");
                        else
                            array_push($expressions, "<a class=\"link\" href=\"#".$row["DiseaseProteinExpressionID"]."\">".$row["DiseaseProteinExpressionID"]."</a><font color=\"green\" title=\"Up regulation\">&uarr;</font>");
                    }
                    echo implode("; ", $expressions);
                    echo "</td></tr>";
                    
                    echo "<tr><td style=\"width:25%\"><b>Gene Ontology Cellular Component</b></td><td style=\"width:75%\">".$proteinRows[0]["GOCC"]."</td>";
                    echo "<tr><td style=\"width:25%\"><b>Gene Ontology Molecular Function</b></td><td style=\"width:75%\">".$proteinRows[0]["GOMF"]."</td>";
                    echo "<tr><td style=\"width:25%\"><b>Gene Ontology Biological Process</b></td><td style=\"width:75%\">".$proteinRows[0]["GOBP"]."</td>";
                    
                    echo "</table>";
            ?>
            
                    <br/>
                    <center><h3>Protein-disease associations</h3></center>
            <?php
                        if(count($diseaseRows) < 1) {
                            if ($keytype === "ID")
                                echo "<center><p>No disease associations found in the database for UniProt accession ID: ".$uniprot." !!</p></center>";
                            else
                                echo "<center><p>No disease associations found in the database for Gene name: ".$key." (UniProt accession ID: ".$uniprot.") !!</p></center>";
                        } else {
            ?>
                            <div style="overflow:auto;">
                                <table class="summary">
                                    <tr><?php foreach($diseaseAttributes as $attr) echo "<th>".$attr."</th>"; ?></tr>
            <?php
                                        foreach($diseaseRows as $row) {
                                            echo "<tr>";
                                            foreach(array_keys($diseaseAttributes) as $attr) {
                                                if ($attr === "MADID")
                                                    echo "<td id=\"".$row[$attr]."\"><a class=\"link\" href=\"disease.php?key=".$row[$attr]."\">".$row[$attr]."</a></td>";
                                                else
                                                    echo "<td>".$row[$attr]."</td>";
                                            }
                                            echo "</tr>";
                                        }
            ?>
                                </table>
                            </div>
            <?php
                        }
            ?>
                    
                    <br/>
                    <center><h3>Disease associated mutations</h3></center>
            <?php
                        if(count($snpRows) < 1) {
                            if ($keytype === "ID")
                                echo "<center><p>No mutations found in the database for UniProt accession ID: ".$uniprot." !!</p></center>";
                            else
                                echo "<center><p>No mutations found in the database for Gene name: ".$key." (UniProt accession ID: ".$uniprot.") !!</p></center>";
                        } else {
            ?>
                                <div style="overflow:auto;">
                                    <table class="summary">
                                        <tr><?php foreach($snpAttributes as $attr) echo "<th>".$attr."</th>"; ?></tr>
            <?php
                                            foreach($snpRows as $row) {
                                                echo "<tr>";
                                                foreach(array_keys($snpAttributes) as $attr) {
        //                                             if ($attr === "dbSNP")
        //                                                 echo "<td><b><a target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/snp/".$row[$attr]."\">".$row[$attr]." <img src=\"resource/redirect-icon.png\" height=\"12px\" width=\"auto\" /></a></b></td>";
                                                    if ($attr === "MPMutationID")
                                                        echo "<td id=\"$row[$attr]\">".$row[$attr]."</td>";
                                                    elseif ($attr === "MADID")
                                                        echo "<td><a class=\"link\" href=\"disease.php?key=".$row[$attr]."\">".$row[$attr]."</a></td>";
                                                    elseif ($attr === "UniProtAccession")
                                                        echo "<td id=\"$row[$attr]\"><a class=\"link\" href=\"protein.php?keytype=ID&key=".$row[$attr]."\">".$row[$attr]."</a></td>";
                                                    elseif ($attr === "length(MPMutationID)")
                                                        echo "<td>".$proteinRows[0]["GeneName"]."</td>";
                                                    elseif($attr === "SourceID")
                                                        echo "<td>".get_SourceID_links($row[$attr])."</td>";
                                                    else
                                                        echo "<td>".$row[$attr]."</td>";
                                                }
                                                echo "</tr>";
                                            }
            ?>
                                    </table>
                                </div>
            <?php
                        }
            ?>
                    
                    <br/>
                    <center><h3>Expression</h3></center>
            <?php
                        if(count($expRows) < 1) {
                            if ($keytype === "ID")
                                echo "<center><p>No expression data found in the database for UniProt accession ID: ".$uniprot." !!</p></center>";
                            else
                                echo "<center><p>No expression data found in the database for Gene name: ".$key." (UniProt accession ID: ".$uniprot.") !!</p></center>";
                        } else {
            ?>
                                <div style="overflow:auto;">
                                    <table class="summary">
                                        <tr><?php foreach($expAttributes as $attr) echo "<th>".$attr."</th>"; ?></tr>
            <?php
                                            foreach($expRows as $row) {
                                                echo "<tr>";
                                                foreach(array_keys($expAttributes) as $attr) {
                                                    if ($attr === "MADID")
                                                        echo "<td><a class=\"link\" href=\"disease.php?key=".$row[$attr]."\">".$row[$attr]."</a></td>";
                                                    elseif ($attr === "DiseaseProteinExpressionID")
                                                        echo "<td id=\"$row[$attr]\">".$row[$attr]."</td>";
                                                    elseif ($attr === "UniProtAccession")
                                                        echo "<td id=\"$row[$attr]\"><a class=\"link\" href=\"protein.php?keytype=ID&key=".$row[$attr]."\">".$row[$attr]."</a></td>";
                                                    elseif ($attr === "length(DiseaseProteinExpressionID)")
                                                        echo "<td>".$proteinRows[0]["GeneName"]."</td>";
                                                    elseif ($attr === "PMID")
                                                        echo "<td>".get_PubMed_links($row[$attr])."</td>";
                                                    else
                                                        echo "<td>".$row[$attr]."</td>";
                                                }
                                                echo "</tr>";
                                            }
            ?>
                                    </table>
                                </div>
            <?php
                        }
                }
            ?>
            
            <br/>
        </div>
    </body>
</html>
