<?php

//main connection file for both admin & front end
$servername = "localhost"; //server
$username = "root"; //username
$password = ""; //password
$dbname = "gallery";  //database

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    // Redirect to error page
    header("Location: error.php");
    exit(); // Stop further execution
}

// Function to prepare SQL statements
function prepare_stmt($sql) {
    global $db;
    return $db->prepare($sql);
}

?>