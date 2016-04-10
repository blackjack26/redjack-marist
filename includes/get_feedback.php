<?php
    require("connect_db.php");
    require("helpers.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo json_encode(mysqli_fetch_array( getRecords( "feedback", "suggestionId", $_POST['id'] ), MYSQLI_ASSOC));
    }

?>