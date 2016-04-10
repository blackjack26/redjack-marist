<html>
    
    <?php
    require('includes/connect_db.php');
    require('includes/header.php');
    validateAdminAccess();
    
    if(!isset($_POST['title'])){
        header("Location: feed.php");
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
        if(isset($_POST['feedback'])){
            // submit form
            addFeedback($_POST['title'], $_POST['feedback'], $_SESSION['admin'], $_POST['id'], $_POST['implemented']);
            if($_POST['duplicate'] == "on"){
                suggestionDuplicate($_POST['id']);
            }
            header("Location: feed.php");
        }
        
        $title = $_POST['title'];
        $content= $_POST['content'];
        $category = $_POST['category'];
    ?>
        
            <div style="padding:10px; max-width: 100%" class="content-box">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p class='suggestion-title'><?php echo $title ?></p>
                        <p class='suggestion-content'><b>Type:</b> <?php echo $category; ?></p>
                        <p class='suggestion-content'><b>Suggestion:</b> <?php echo $content; ?></p>
                    </div>
                </div>
            </div>
    <?php
    }
    ?>
    
    
    <body>
        <div id= "feedback-suggestion"> 
        <!--displays the suggestion that was clicked by the admin-->
        </div>
        <form id = "feedback_form" action="feedback.php" method = "POST">
            <div class="form-group">
        	<label for="title">Title*</label><br />
        	<input required id="title" placeholder="Title" class="form-control" name="title" type="text" value="" size="30" /><br />
        	
        	<label for="feedback">Feedback*</label><br />
        	<textarea required id="feedback" placeholder="What do you feel about this suggestion?" class="form-control" name="feedback" type="text" value="" rows="7" ></textarea><br />	
            
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="implemented"> Implement Suggestion
                </label>
            </div><br />
            
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="duplicate"> Duplicate Suggestion
                </label>
            </div><br />
            
            <input type="hidden" name="id" value="<?= $_POST['id'] ?>" />
            
            <a class="btn btn-danger" href="feed.php" role="button">Cancel</a>
            <input id="feedback_btn" class="btn btn-primary" type="submit" value="Submit Feedback!">
        </form>
    </body>
    
    <script>
        $("input[name=duplicate]").change(function(){
            if($(this).is(":checked")){
                $("input[name=implemented]").attr("disabled", true);
            } else {
                $("input[name=implemented]").attr("disabled", false);
            }
        });
        
        $("input[name=implemented]").change(function(){
            if($(this).is(":checked")){
                $("input[name=duplicate]").attr("disabled", true);
            } else {
                $("input[name=duplicate]").attr("disabled", false);
            }
        });
    </script>
</html>