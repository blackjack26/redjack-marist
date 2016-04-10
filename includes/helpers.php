<?php
error_reporting(E_ERROR);
function addNewUser ( $infoArray ){
    global $dbc;
    
    $fname = ucfirst(strtolower(trim($infoArray['fname'])));
    $lname = ucfirst(strtolower(trim($infoArray['lname'])));
    $username = $infoArray['username'];
    $password = $infoArray['password'];
    $email = trim($infoArray['email']);
    $role = $infoArray['role'];
    
    $query = "INSERT INTO users VALUES (null, '$fname', '$lname', '$username', SHA2('$password', 224), '$email', '$role');";
    $results = mysqli_query( $dbc, $query );
    
    if(!$results){
        echo 'Error Adding User';
        return false;
    } else {
        return true;
    }
}

function addFeedback($title, $content, $username, $suggestionId, $implemented){
    global $dbc;
    
    $query = "INSERT INTO feedback VALUES (null, '$title', '$content', '$username', NOW(), '$suggestionId');";
    $results = mysqli_query($dbc, $query);
    
    if(!$results){
        echo 'Error Adding Feedback';
    } else {
        updateSuggestion($suggestionId, ($implemented == "on") ? 1 : 0);
    }
}

function updateSuggestion($sid, $implemented){
    global $dbc;
    
    $query = "UPDATE suggestions SET reviewed = 1, implemented = $implemented WHERE id = $sid;";
    $results = mysqli_query($dbc, $query);
}

function addSuggestion($title, $content, $fname, $lname, $email, $category, $class, $duplicate){
    global $dbc;
    
    $sanitized = mysqli_real_escape_string($dbc, trim($content));
    
    $query = "INSERT INTO suggestions VALUES (null, '$title', '$sanitized', NOW(), '$fname', '$lname', '$email', '$category', 0, 0, 0, 0, '$class', $duplicate);";
    $results = mysqli_query($dbc, $query);
    if(!$results){
        header("Location: index.php?success=false&msg=suggestion");
    } else {
        header("Location: index.php?success=true&msg=suggestion");
    }
}

function suggestionDuplicate($sid){
    global $dbc;
    
    $query = "UPDATE suggestions SET duplicate = 1 WHERE id = $sid;";
    $results = mysqli_query($dbc, $query);
}

function addVote($uId, $sId, $up, $down){
    global $dbc;
    
    $query = "INSERT INTO votes VALUES ($uId, $sId, $up, $down, NOW());";
    $results = mysqli_query($dbc, $query);
    
    if(!$results){
        echo 'Error Adding Vote: ' . $query;
    }
}

function voteExists($uId, $sId){
    global $dbc;
    $query = "SELECT * FROM votes WHERE uid=$uId AND sid=$sId";
    $results = mysqli_query( $dbc, $query );
    if(mysqli_num_rows($results) > 0){
        return true;
    }
    return false;
    mysqli_free_result($results);
}

function getRecords( $table, $field, $value ){
    global $dbc;
    $query = "SELECT * FROM $table WHERE $field='$value'";
    $results = mysqli_query( $dbc, $query );
    if($results){
        return $results;
    }
    mysqli_free_result($results);
}

function getStat($query){
    global $dbc;
    $results = mysqli_query($dbc, $query);
    if($results){
        return mysqli_fetch_array($results, MYSQLI_ASSOC);
    }
    mysqli_free_result($results);
}

function getQuery($query){
    global $dbc;
    $results = mysqli_query($dbc, $query);
    if($results){
        return $results;
    }
    mysqli_free_result($results);
}

function getRecordsTable( $table ){
    global $dbc;
    $query = "SELECT * FROM $table";
    $results = mysqli_query( $dbc, $query );
    if($results){
        return $results;
    }
    if($results != null)
        mysqli_free_result($results);
}

function getTrending(){
    global $dbc;
    $query = "SELECT * FROM suggestions WHERE reviewed = 0 ORDER BY up DESC";
    $results = mysqli_query( $dbc, $query );
    if($results){
        return $results;
    }
    if($results != null)
        mysqli_free_result($results);
}

function getOldestSuggestions(){
    global $dbc;
    $query = "SELECT * FROM suggestions ORDER BY reviewed ASC, post_date ASC";
    $results = mysqli_query( $dbc, $query );
    if($results){
        return $results;
    }
    if($results != null)
        mysqli_free_result($results);
}

function validEmailAddress( $email ){
    $re = "/^([a-z0-9_\\.-]+\\@[\\da-z\\.-]+\\.[a-z\\.]{2,6})$/m";
    return preg_match($re, $email);
}

function validCredentials($login, $password){
    global $dbc;
    
    $query = 'SELECT * FROM users WHERE (username = "' . 
        $login . '" OR email = "' . 
        $login . '") AND password = SHA2("' .
        $password . '", 224)';

    $results = mysqli_query( $dbc, $query );

    if( $results && mysqli_num_rows($results) > 0){
        mysqli_free_result( $results );
        return true;
    } else {
        return false;
    }
}

function adminLogin($login, $password){    
    global $dbc;
    
    $error = "";

    if( validCredentials($login, $password) ){
        $data = getUserDataByUsername( $login );
        if(!empty($data)){
            if($data['role'] == "Admin")
                $_SESSION["admin"] = $login;
            else if($data['role'] == "User")
                $_SESSION["user"] = $login;
                
            echo $data['role'];
        }else{
            $data = getUserDataByEmail( $login );
            $_SESSION["admin"] = $data["username"];
            if($data['role'] == "Admin")
                $_SESSION["admin"] = $data["username"];
            else if($data['role'] == "User")
                $_SESSION["user"] = $data["username"];
        }
        header("Location: feed.php"); // Changes the current page
    } else {
        $error = "Invalid Credentials";
    }
    
    return $error;
}

function logout(){
    if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
        session_destroy();
        
        header("Location: login.php");
        exit();
    }
}

function getUserDataByUsername( $username ){
    global $dbc;
    
    $query = 'SELECT id, fname, lname, email, role, division ' .
        'FROM users WHERE username="' . $username . '";';
    
    $results = mysqli_query( $dbc, $query );
    
    if ( $results ){
        return mysqli_fetch_array( $results, MYSQLI_ASSOC );
    }
}

function getUserDataByEmail( $email ){
    global $dbc;
    
    $query = 'SELECT id, fname, lname, email, role, division ' .
        'FROM users WHERE email="' . $email . '";';
    
    $results = mysqli_query( $dbc, $query );
    
    if ( $results ){
        return mysqli_fetch_array( $results, MYSQLI_ASSOC );
    }
}

function validateAdminAccess(){
    // If user accessed page without logging in, redirect to login page
    if(!hasAdminAccess()){
        header("Location: index.php");
    }
}

function hasAdminAccess(){
    session_start();
    return isset($_SESSION['admin']);
}

function isLoggedIn(){
    session_start();
    return isset($_SESSION['admin']) || isset($_SESSION['user']);
}

function thumbsUp($suggestionId){
    global $dbc;
    $suggData = mysqli_fetch_array( getRecords("suggestions", "id", $suggestionId), MYSQLI_ASSOC );
    
    $query = "UPDATE suggestions 
              SET up = ". ($suggData['up']+1) ."
              WHERE id = $suggestionId";

    $results = mysqli_query($dbc,$query);

    return $suggData['up']+1 ;
}

function thumbsDown($suggestionId){
    global $dbc;
    
    $suggData = mysqli_fetch_array( getRecords("suggestions", "id", $suggestionId), MYSQLI_ASSOC );
    
    $query = "UPDATE suggestions 
              SET down = ". ($suggData['down']+1) ."
              WHERE id = $suggestionId";

    $results = mysqli_query($dbc,$query);

    return $suggData['down']+1 ;
}

?>