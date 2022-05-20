<?php

/*
if($_POST) {
    $vars = array();
    foreach($_POST as $field => $value) {
        array_push($vars,"$field:".json_encode($value)); // Push to $vars array so we can just implode() it, escape value
    }
    echo "<script>var post = {".implode(", ",$vars)."}</script>\n"; // Implode array, javascript will interpret as dictionary

}
*/

$data = $_POST;

// sanitize first
echo "\n\n:: Data received via POST ::\n\n";
print_r($data);









