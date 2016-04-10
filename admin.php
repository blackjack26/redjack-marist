<html>
    <?php
        require('includes/connect_db.php');
        require("includes/header.php");
        if(!hasAdminAccess() || getUserDataByUsername($_SESSION['admin'])['division'] != ""){
            header("Location: index.php");
        }
        
        $type = "";
        $msgB = "";
        $msg = "";
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_POST['remove_admin'])){
                $deleteError = deleteAdmin($_POST['username']);
                if($deleteError){ 
                    $type = "danger";
                    $msgB = "Error";
                    $msg = "Admin not deleted!";
                }else{
                    $type = "success";
                    $msgB = "Success";
                    $msg = "Admin successfully deleted!";
                } 
            }
        }
    ?>
    <body>
        
        <?php if($type != ""){ ?>
        <div id="msg-buffer">
            <div id="msg_alert" class="alert alert-<?php echo $type; ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong><?php echo $msgB; ?>!</strong> <?php echo $msg; ?>
            </div>
        </div>
        <?php } ?>
        
        <br>
        <form class="new-user-form" action="signup.php" method="POST">
            <div id="suggest-btn" class="btn-group btn-group-lg" role="group">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add New Admin</button>
            </div><br />
            <input type="hidden" name="add_admin" value="true" />
        </form>
        <h3 class="form_header">Remove Admin</h3>
        <form action="admin.php" method="POST">
            <label for="username">Username*</label><br />
    		<input required id="username" placeholder="Username" class="form-control" name="username" type="text" value="" size="30" /><br />
            <input type="hidden" name="remove_admin" value="true" />
    		
    	    <input class="btn btn-danger" type="submit" value="Remove Admin">
        </form>
    </body>
</html>