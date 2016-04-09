<html>
  
<?php
    require('includes/connect_db.php');
    require('includes/header.php');
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($_GET['logout']){
            logout();
        }
    }
    
    if(hasAdminAccess()){
        header("Location: feed.php");
        exit();
    }
    
    $error = "";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['username']) && isset($_POST['password'])){
            $login = $_POST['username'];
            $password = $_POST['password'];
            echo $login . " " + $password;
            $error = adminLogin($login, $password);
        }
    }
?>

<body>
    <h3 class="form_header">User Login</h3>
    <form id="login-form" action="login.php" method="POST" enctype="multipart/form-data">
    	<div class="form-group">
    	    <label for="username">Username/Email</label><br />
    		<input id="username" class="form-control" name="username" type="text" value="" size="40" /><br />
    		
    	    <label for="password">Password</label><br />
    		<input id="password" class="form-control" name="password" type="password" value="" size="40" /><br />
    		
            <a class="btn btn-danger" href="index.php" role="button">Cancel</a>
    	    <input id="sugg_cont_btn" class="btn btn-primary" type="submit" value="Sign In">
    	    <p id="sign-up-link">Don't have an account? <a href="signup.php">Sign Up!</a></p>
    	</div>
    </form>	

</body>
    
</html>