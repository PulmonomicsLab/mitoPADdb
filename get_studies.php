<?php
    include('db.php');

    $key = urldecode($_GET['key']);
//     echo $key."<br/>";

    $conn = connect();

    $studyAttributes = array(
        "StudyID"=>"Study ID",
        "DiseaseCategory"=>"Disease category",
        "DiseaseName"=>"Disease name",
        "Sample"=>"Sample",
        "FoldChangeSubjects"=>"Log<sub>2</sub> Fold Change Subjects",
        "ConditionStateRatio"=>"Condition/State",
        "GEOAccession"=>"GEO Accession",
    );

    $studyQuery = "select ".implode(",", array_keys($studyAttributes))." from study_metadata where DiseaseCategory=?;";
//     echo $studyQuery."<br/>";
    $studyStmt = $conn->prepare($studyQuery);
    $studyStmt->bind_param("s", $key);
    $studyStmt->execute();
    $studyRows = execute_and_fetch_assoc($studyStmt);
    $studyStmt->close();
    closeConnection($conn);
    $rowsJSON = json_encode($studyRows);
    echo $rowsJSON;
?>

