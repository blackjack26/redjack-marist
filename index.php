<html>
<?php
    require('includes/connect_db.php');
    require('includes/header.php');
    session_start();
?>
<body>
    
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($_GET['success'])){
                $msg = "";
                $type = $_GET['success']  == "true" ? "success" : "danger";
                $typeC = $_GET['success'] == "true" ? "Success" : "Error";
                
                if($_GET['msg'] == "suggestion"){
                    if($_GET['success'] == "true")
                        $msg = "Your suggestion will be reviewed shortly!";
                    else {
                        $msg = "There were errors and your suggestion couldn't be processed.";
                    }
                }
                ?>
                <div id="msg-buffer">
                    <div id="msg_alert" class="alert alert-<?php echo $type; ?> alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong><?php echo $typeC; ?>!</strong> <?php echo $msg; ?>
                    </div>
                </div>
                <?php
            }
        }
    ?>
    
    <form id = "main_content" action="new_suggestion.php"> 
        <!--div class="input-group input-group-lg">
            <input type="text" class="form-control" placeholder="Suggestions...">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">Next</button>
            </span>
        </div-->
        <h1 id = "great" > Make Marist Great Again! </h1>
        <div id="suggest-btn" class="btn-group btn-group-lg" role="group">
            <button class="btn btn-default" type="submit">Let me suggest something...</button>
        </div><br />
        <div id="suggest-btn" class="btn-group btn-group-lg" role="group">
            <a class="btn btn-default" href="trending.php" role="button" >
                Trending suggestions&nbsp;
                <span class="glyphicon glyphicon-sunglasses" aria-hidden="true"></span>
            </a>
        </div>
    </form>
</body>
</html>