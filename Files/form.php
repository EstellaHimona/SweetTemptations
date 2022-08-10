<?php 
  include "style.css"; 
?>

<?php

    // include connection settings
    require_once "connectdb.php";

    // get a list of all students in the database
    $query = "SELECT * FROM `register`";

    // perform the query
    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc());
        echo "<pre>";
        print_r($row);
        echo "</pre>";      
    }
    else {
        echo "SQL Error:" . $db->error;
    }

?>