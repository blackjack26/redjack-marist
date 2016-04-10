<html>
    <?php
        require("includes/header.php");
        require_once("includes/connect_db.php");
        
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            header("Location: new_suggestion.php");
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (isset($_POST['form_complete'])){
                addSuggestion($_POST['title'], 
                            $_POST['content'], 
                            $_POST['fname'], 
                            $_POST['lname'], 
                            $_POST['email'], 
                            $_POST['category'],
                            $_POST['class'],
                            0);
            } else if(!isset($_POST['first_page'])){
                header("Location: new_suggestion.php");
            }
            
            $title = $_POST['title'];
            $content = $_POST['suggestion'];
            $category = $_POST['category'];
            $userData = array();
            if(isLoggedIn())
                $userData = getUserDataByUsername( isset($_SESSION['admin']) ? $_SESSION['admin'] : $_SESSION['user'] );
        }
    ?>
    <body>
        <h3 class="form_header">New Suggestion<br><small>About You!</small></h3>
        <form id="suggest_form" action="new_suggestion2.php" method="POST" enctype="multipart/form-data">
        	<div class="form-group">
        	    <label for="fname">First Name</label><br />
        		<input id="fname" class="form-control" name="fname" type="text" value="<?= $userData['fname'] ?>" size="40" <?php if($userData['fname'] != "") echo "readonly"; ?>/><br />
        		
        	    <label for="lname">Last Name</label><br />
        		<input id="lname" class="form-control" name="lname" type="text" value="<?= $userData['lname'] ?>" size="40" <?php if($userData['lname'] != "") echo "readonly"; ?>/><br />
        		<label for="class">Are you a student?</label><br />
        		<select name="class" class="form-control">
        		    <option value="">No</option>
                    <option>Freshman</option>
                    <option>Sophomore</option>
                    <option>Junior</option>
                    <option>Senior</option>
                </select><br />
        		
        		<label for="email">Email</label><br />
        		<input id="email" class="form-control" name="email" type="text" value="<?= $userData['email'] ?>" size="40" <?php if($userData['email'] != "") echo "readonly"; ?>/><br />
        		
                <input type="hidden" name="title" value="<?= $title ?>" />
                <input type="hidden" name="content" value="<?= $content ?>" />
                <input type="hidden" name="category" value="<?= $category ?>" />
                <input type="hidden" name="form_complete" value="true" />
        
                <a class="btn btn-danger" href="index.php" role="button">Cancel</a>
        	    <input id="sugg_cont_btn" class="btn btn-default" type="submit" value="Finish!">
        	</div>
        </form>	
    </body>
</html>