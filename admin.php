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
        <form style="margin:10px" action="admin.php" method="POST">
            <label for="username">Username*</label><br />
    		<input required id="username" placeholder="Username" class="form-control" name="username" type="text" value="" size="30" /><br />
            <input type="hidden" name="remove_admin" value="true" />
    		
    	    <input class="btn btn-danger" type="submit" value="Remove Admin">
        </form>
        
        <?php
            $users = getQuery("SELECT * FROM users WHERE role = 'Admin';");
        ?>
        
        <h2 style="margin-left:15px">Admin Table</h2>
        <div style="width:100%;overflow-x:scroll" class="container">
          <table class="table table-responsive table-hover table-striped" >
            <thead>
              <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Division</th>
              </tr>
            </thead>
            <tbody>
                
            <?php while( $row = mysqli_fetch_array($users, MYSQLI_ASSOC ) ){ ?>
              <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['division']; ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>

    </body>
</html>