<?php
    require("connect_db.php");
    require("helpers.php");

    if(isLoggedIn()){
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $userId = getUserDataByUsername( isset($_SESSION['admin']) ? $_SESSION['admin'] : $_SESSION['user'] )['id'];
            
            if(!voteExists($userId, $_POST['id'])){
                if(isset($_POST['id']) && $_POST['up']){
                    addVote($userId, $_POST['id'], 1, 0);
                    echo thumbsUp($_POST['id']);
                } else if(isset($_POST['id']) && $_POST['down']){
                    addVote($userId, $_POST['id'], 0, 1);
                    echo thumbsDown($_POST['id']);
                }
            } else {
                echo "duplicate";
            }
        }
    } else {
        echo "login";
    }

?>