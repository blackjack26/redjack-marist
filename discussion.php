<html>
    <?php
        require('includes/connect_db.php');
        require('includes/header.php');
        validateAdminAccess();
 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $sid = $_POST['id'];
            if(isset($_POST['uid'])){
                addDiscussionMsg($_POST['uid'], $_POST['sid'], $_POST['message']);
                $sid = $_POST['sid'];
            }
            
            $userData = getUserDataByUsername( $_SESSION['admin']);
            $currentUserId = $userData['id'];
            $currentUsername = $_SESSION['admin'];
            
            $discussion = getQuery("SELECT * FROM discussion WHERE sid=$sid");
        }
        
        $title = $_POST['title'];
        $content= $_POST['content'];
        $category = $_POST['category'];
    ?>
        
    <div style="padding:10px; max-width: 100%" class="content-box">
        <div class="panel panel-default">
            <div class="panel-body" id="suggest-body">
                <p class='suggestion-title'><?php echo $title ?></p>
                <p class='suggestion-content'><b>Type:</b> <?php echo $category; ?></p>
                <p class='suggestion-content'><b>Suggestion:</b> <?php echo $content; ?></p>
            </div>
        </div>
    </div>
    <?php
        if(!isset($_POST['id']) && !isset($_POST['sid'])){
            header("Location: feed.php");
        }
 
 ?>
 <link rel="stylesheet" href="css/discussion.css" type="text/css" />

<div class="container" id="chat-container">
    <div class="row">
        <div class="chat-box">
            <div class="panel panel-primary" id="my-panel-primary">
                <div class="panel-heading pane-top">
                   Suggestion Discussion
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                        <ul class="dropdown-menu slidedown">
                            <li class= "chat-list"><a href="discussion.php"><span class="glyphicon glyphicon-refresh">
                            </span>Refresh</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <ul class="chat">
                        
                        <?php
                        while( $row = mysqli_fetch_array($discussion, MYSQLI_ASSOC ) ){ 
                            $userId = $row['uid'];
                            $currentUser = false;
                            $username = "";
                            if($userId == $currentUserId){
                                $username = $currentUsername;
                                $currentUser = true;
                            } else {
                                $username = mysqli_fetch_array(getQuery("SELECT username FROM users WHERE id = $userId"), MYSQLI_ASSOC )['username'];
                            }
                            
                        ?>
                        
                        <li class="left clearfix <?php echo ($currentUser) ? "current-user" : ""; ?>">
                            <!--span class="chat-img pull-left">
                                <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                            </span-->
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font"><?php echo $username; ?></strong> <small class="pull-right text-muted">
                                    <span class="glyphicon glyphicon-time"></span> <?php echo $row['post_time']; ?></small>
                                </div>
                                <p class="chat-txt">
                                    <?php echo $row['message']; ?>
                                </p>
                            </div>
                        </li>
                        
                        <?php } ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm dis-btn" id="btn-chat">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="user-id" value="<?= $currentUserId ?>" />
<input type="hidden" id="sugg-id" value="<?= $sid ?>" />
<input type="hidden" id="sugg-content" value="<?= $content ?>" />
<input type="hidden" id="sugg-title" value="<?= $title ?>" />
<input type="hidden" id="sugg-cat" value="<?= $category ?>" />
<script>
    $("#btn-chat").click(function(){
         post("discussion.php", { 
            uid: $("#user-id").val(),
            sid: $("#sugg-id").val(),
            message: $("#btn-input").val(),
            title: $("#sugg-title").val(),
            category: $("#sugg-cat").val(),
            content: $("#sugg-content").val()
        }); 
    });
    
    $(function(){
        $("#my-panel-primary .panel-body").animate({ 
            scrollTop: $("#my-panel-primary .panel-body").prop("scrollHeight") - $("#my-panel-primary .panel-body").height() 
        }, 1000); 
    });
    
    function post(path, params, method) {
        method = method || "post"; // Set method to post by default if not specified.
    
        // The rest of this code assumes you are not using a library.
        // It can be made less wordy if you use one.
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);
    
        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);
    
                form.appendChild(hiddenField);
             }
        }
    
        document.body.appendChild(form);
        form.submit();
    }
</script>
</html>
    
