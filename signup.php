<html>
    <?php
    require('includes/connect_db.php');
    require('includes/header.php');
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    	if(!isset($_POST['add_admin'])){
	    	if(!isset($_POST['role'])){
	    		$_POST['role'] = "User";
	    	}
	    	if(!isset($_POST['division'])){
	    		$_POST['division'] = "";
	    	}
	    	if(addNewUser($_POST)){
	    		if($_POST['role'] == "User"){
		    		session_start();
		    		$_SESSION['user'] = $_POST['username'];
	    			header("Location: index.php");
	    		} else {
	    			header("Location: admin.php");
	    		}
	    	}
    	}
    }
    
?>

<body> 
    <h3 class="form_header">Create your account<br><small>Account Info</small></h3>
	<form id="contact_form" action="signup.php" method="POST" enctype="multipart/form-data">
		<div class="form-group">
	    	<label for="username" class= "signup-control">Username*</label><br />
			<input id="username" required class="form-control contact-control" name="username" type="text" value="" size="30" /><br />
			
			<label for="password" class= "signup-control">Password*</label><br />
			<input id="password" required class="form-control contact-control" name="password" type="password" value="" size="30" /><br />
			
			<label for="fname" class= "signup-control">First name*</label><br />
			<input id="fname" required class="form-control contact-control"name="fname" type="text" value="" size="30" /><br />
			
			<label for="lname" class= "signup-control">Last name*</label><br />
			<input id="lname" required class="form-control contact-control" name="lname" type="text" value="" size="30" /><br />
	
			<label for="email" class= "signup-control">Email address*</label><br />
			<input id="email" required class="form-control contact-control" name="email" type="text" value="" size="30" /><br />
			
			<?php if(isset($_POST['add_admin'])){ ?>
				<input type="hidden" name="role" value="Admin" />
				<label for="division">Division*</label><br />
        		<select name="division" class="form-control">
                    <option value="">None</option>
                    <option>Student Life</option>
                    <option>Administration</option>
                    <option>Academics</option>
                    <option>Athletics</option>
                    <option>Housing</option>
                    <option>Safety</option>
                </select><br />
			<?php } ?>
	
	        <a class="btn btn-danger" href="login.php" role="button">Cancel</a>
			<input id="sugg_cont_btn" class= "btn btn-default" type="submit" value="Sign up!" />
	</form>						
</body>
</html>