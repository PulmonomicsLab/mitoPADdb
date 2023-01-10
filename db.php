<?php
    $dbConfigFileName = "config/config_db.xml";

    function getDBParams() {
        global $dbConfigFileName;
        $xmlData = simplexml_load_file($dbConfigFileName);
        $server = $xmlData->server;
        $uname = $xmlData->uname;
        $pass = $xmlData->pass;
        $dbname = $xmlData->dbname;
        
        return array("server"=>$server, "uname"=>$uname, "pass"=>$pass, "dbname"=>$dbname);
    }
    
    function connect() {
        $dbParams = getDBParams();
//         $conn = new mysqli($server, $uname, $pass, $dbname);
        $conn = new mysqli($dbParams["server"], $dbParams["uname"], $dbParams["pass"], $dbParams["dbname"]);
        return $conn;
    }
    
    function closeConnection($conn) {
        $conn->close();
    }
    
    function execute_and_fetch_assoc($stmt, $types = false, $params = false) {
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->store_result();
       
        // get column names
        $metadata = $stmt->result_metadata();
        $fields = $metadata->fetch_fields();

        $results = [];
        $ref_results = [];
        foreach($fields as $field){
            $results[$field->name]=null;
            $ref_results[]=&$results[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $ref_results);

        $data = array();
        $i=0;
        while ($stmt->fetch()) {
            $c = 0;
            $new_res = array();
            foreach($results as $f => $v) {
                $new_res[$f] = $v;
                $c++;
            }
//             echo implode(",", $new_res);
            array_push($data, $new_res);
        }

//         echo count($data);
//         foreach($data as $d)
//             echo implode(",", $d)."<br/>";

        $stmt->free_result();
        return  $data;
    }
?>
