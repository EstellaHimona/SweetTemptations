<?php
    $db = new mysqli('smcse-stuproj00.city.ac.uk', 'adbt208', '200010135', 'adbt208');
    if ($db -> connect_error) {
        printf("Connection failer: %s\n", $db -> connect_error);
        exit();
    }

?> 