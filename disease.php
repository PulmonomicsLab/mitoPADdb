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
    
    $key = $_GET['key'];
//     echo $key."<br/>";
    
    $conn = connect();
    
    $diseaseAttributes = array(
        "MADID"=>"Disease ID",
        "DiseaseName"=>"Disease name",
        "DiseaseCategory"=>"Disease category",
        "ExternalDiseaseIDs" => "External Disease IDs",
        "MeSHTreeNumber" => "MeSH Tree Number",
    );
    $diseaseQuery = "select ".implode(",", array_keys($diseaseAttributes))." from disease where MADID=?;";
//     echo $diseaseQuery."<br/>";
    $diseaseStmt = $conn->prepare($diseaseQuery);
    $diseaseStmt->bind_param("s", $key);
    $diseaseStmt->execute();
    $diseaseRows = execute_and_fetch_assoc($diseaseStmt);
    $diseaseStmt->close();
//     echo implode(",", $diseaseRows[0])."<br/>";
    
    $proteinAttributes = array(
        "UniProtAccession"=>"UniProt accession ID",
        "GeneName"=>"Gene name",
        "ProteinDiseaseAssociationID"=>"Association ID"
    );
    $proteinQuery = "select protein.UniProtAccession,GeneName,ProteinDiseaseAssociationID from protein inner join protein_disease_association on protein.UniProtAccession=protein_disease_association.UniProtAccession where MADID=?;";
//     echo $proteinQuery."<br/>";
    $proteinStmt = $conn->prepare($proteinQuery);
    $proteinStmt->bind_param("s", $key);
    $proteinStmt->execute();
    $proteinRows = execute_and_fetch_assoc($proteinStmt);
    $proteinStmt->close();
//     echo count($proteinRows)."<br/>";
//     foreach($proteinRows as $row) {
//         echo implode(",", array_keys($row))."<br/>";
//         echo implode(",", $row)."<br/>";
//     }
    
    $snpAttributes = array(
        "MPMutationID"=>"Mutation ID",
        "dbSNPID"=>"dbSNP ID",
        "NucleotideVariation"=>"Nucleotide mutation",
        "AminoAcidChange"=>"Amino acid change",
        "nucleotide_variation.UniProtAccession"=>"UniProt accession ID",
        "GeneName"=>"Gene name",
        "MADID"=>"Disease ID",
        "SourceID"=>"Source ID",
//         "PMID"=>"PMID",
    );
    $snpQuery = "select ".implode(",", array_keys($snpAttributes))." from nucleotide_variation inner join protein on nucleotide_variation.UniProtAccession=protein.UniProtAccession where MADID=?;";
//     echo $snpQuery."<br/>";
    $snpStmt = $conn->prepare($snpQuery);
    $snpStmt->bind_param("s", $key);
    $snpStmt->execute();
    $snpRows = execute_and_fetch_assoc($snpStmt);
    $snpStmt->close();
//     echo count($snpRows)."<br/>";
//     foreach($snpRows as $row) {
//         echo implode(",", array_keys($row))."<br/>";
//         echo implode(",", $row)."<br/>";
//     }
    
    $expAttributes = array(
        "DiseaseProteinExpressionID"=>"Expression ID",
        "ExpressionVariation"=>"Expression variation",
        "ExpressionMolecule"=>"Expression molecule",
        "CellLine"=>"Cell line",
        "Sample"=>"Sample",
        "Method"=>"Method",
        "expression.UniProtAccession"=>"UniProt accession ID",
        "GeneName"=>"Gene name",
        "MADID"=>"Disease ID",
        "Remarks"=>"Remarks",
        "PMID"=>"PubMed ID",
    );
    $expQuery = "select ".implode(",", array_keys($expAttributes))." from expression inner join protein on expression.UniProtAccession=protein.UniProtAccession where MADID=?;";
//     echo $expQuery."<br/>";
    $expStmt = $conn->prepare($expQuery);
    $expStmt->bind_param("s", $key);
    $expStmt->execute();
    $expRows = execute_and_fetch_assoc($expStmt);
    $expStmt->close();
//     echo count($expRows)."<br/>";
//     foreach($expRows as $row) {
//         echo implode(",", array_keys($row))."<br/>";
//         echo implode(",", $row)."<br/>";
//     }
//     
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
                if(count($diseaseRows) < 1)
                    echo "<center><p>Error !!! Disease ID: ".$key." does not exist in the database.</p></center>";
                else {
                    echo "<center><h2>Disease ID: ".$key."</h2></center>";
                    echo "<table class=\"details\" border=\"1\">";
//                     echo "<tr><th>Attribute</th><th>Value</th></tr>";
                    
                    foreach ($diseaseAttributes as $name=>$fname) {
                        if ($name !== "MADID") {
                            echo "<tr>";
                            echo "<td style=\"width:25%\"><b>".$fname."</b></td>";
                            echo "<td style=\"width:75%\">".$diseaseRows[0][$name]."</td>";
                            echo "</tr>";
                        }
                    }
                    
                    echo "<tr><td style=\"width:25%\"><b>Protein associations</b></td><td style=\"width:75%\">";
                    $proteins = array();
                    foreach($proteinRows as $row)
                        array_push($proteins, "<a class=\"link\" href=\"#".$row["UniProtAccession"]."\">".$row["GeneName"]."</a>");
                    echo implode("; ", $proteins);
                    echo "</td></tr>";
                    
                    echo "<tr><td style=\"width:25%\"><b>Nucleotide mutations</b></td><td style=\"width:75%\">";
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
                            array_push($expressions, "<a class=\"link\" href=\"#".$row["DiseaseProteinExpressionID"]."\">".$row["DiseaseProteinExpressionID"]."</a><font color=\"darkgreen\" title=\"Up regulation\">&uarr;</font>");
                    }
                    echo implode("; ", $expressions);
                    echo "</td></tr>";
                    
                    echo "</table>";
                }
            ?>
            
            <br/>
            <center><h3>Protein-disease associations</h3></center>
            <?php
                if(count($proteinRows) < 1)
                    echo "<center><p>No protein associations found in the database for Disease ID: ".$key." !!</p></center>";
                else {
            ?>
                        <div style="overflow:auto;">
                            <table class="summary">
                                <tr><?php foreach($proteinAttributes as $attr) echo "<th>".$attr."</th>"; ?></tr>
            <?php
                                    foreach($proteinRows as $row) {
                                        echo "<tr>";
                                        foreach(array_keys($proteinAttributes) as $attr) {
                                            if ($attr === "UniProtAccession")
                                                echo "<td id=\"".$row[$attr]."\"><a class=\"link\" href=\"protein.php?keytype=ID&key=".$row[$attr]."\">".$row[$attr]."</a></td>";
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
            <center><h3>Disease associated nucleotide mutations</h3></center>
            <?php
                if(count($snpRows) < 1)
                    echo "<center><p>No nucleotide mutations found in the database for Disease ID: ".$key." !!</p></center>";
                else {
            ?>
                    <div style="overflow:auto;">
                        <table class="summary">
                            <tr><?php foreach($snpAttributes as $attr) echo "<th>".$attr."</th>"; ?></tr>
            <?php
                                foreach($snpRows as $row) {
                                    echo "<tr>";
                                    foreach(array_keys($snpAttributes) as $attr) {
                                        if ($attr === "MPMutationID")
                                            echo "<td id=\"$row[$attr]\">".$row[$attr]."</td>";
                                        elseif ($attr === "nucleotide_variation.UniProtAccession")
                                            echo "<td><a class=\"link\" href=\"protein.php?keytype=ID&key=".$row["UniProtAccession"]."\">".$row["UniProtAccession"]."</a></td>";
                                        elseif ($attr === "MADID")
                                            echo "<td id=\"$row[$attr]\"><a class=\"link\" href=\"disease.php?key=".$row[$attr]."\">".$row[$attr]."</a></td>";
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
                if(count($expRows) < 1)
                    echo "<center><p>No expression data found in the database for Disease ID: ".$key." !!</p></center>";
                else {
            ?>
                        <div style="overflow:auto;">
                            <table class="summary">
                                <tr><?php foreach($expAttributes as $attr) echo "<th>".$attr."</th>"; ?></tr>
            <?php
                                    foreach($expRows as $row) {
                                        echo "<tr>";
                                        foreach(array_keys($expAttributes) as $attr) {
                                            if ($attr === "expression.UniProtAccession")
                                                echo "<td><a class=\"link\" href=\"protein.php?keytype=ID&key=".$row["UniProtAccession"]."\">".$row["UniProtAccession"]."</a></td>";
                                            elseif ($attr === "DiseaseProteinExpressionID")
                                                echo "<td id=\"$row[$attr]\">".$row[$attr]."</td>";
                                            elseif ($attr === "MADID")
                                                echo "<td id=\"$row[$attr]\"><a class=\"link\" href=\"disease.php?key=".$row[$attr]."\">".$row[$attr]."</a></td>";
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
            ?>
            
            <br/>
        </div>
    </body>
</html>

