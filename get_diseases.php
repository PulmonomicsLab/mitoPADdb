<?php
    include('db.php');

    $key = urldecode($_GET['key']);
//     echo $key."<br/>";

    $conn = connect();

    $diseaseAttributes = array(
        "MADID"=>"Disease ID",
        "DiseaseName"=>"Disease name",
        "DiseaseCategory"=>"Disease category",
    );

    $diseaseQuery = "select ".implode(",", array_keys($diseaseAttributes))." from disease where DiseaseCategory=?;";
//     echo $diseaseQuery."<br/>";
    $diseaseStmt = $conn->prepare($diseaseQuery);
    $diseaseStmt->bind_param("s", $key);
    $diseaseStmt->execute();
    $diseaseRows = execute_and_fetch_assoc($diseaseStmt);
    $diseaseStmt->close();
    closeConnection($conn);
    $rowsJSON = json_encode($diseaseRows);
    echo $rowsJSON;
?>
