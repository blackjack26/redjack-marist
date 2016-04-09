<?php

    // A simple PHP script demonstrating how to connect to MySQL.

    $servername = getenv('IP');
    $username = getenv('C9_USER');
    $password = "";
    $database = "marist";
    $dbport = 3306;

    // Create connection
    $dbc = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($dbc->connect_error) {
        die("Connection failed: " . $dbc->connect_error);
    } 
    // echo "Connected successfully (".$dbc->host_info.")<br>";
    
    mysqli_set_charset( $dbc, 'utf8' ) ;

?>