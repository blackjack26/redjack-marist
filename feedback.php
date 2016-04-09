<html>
    
    <?php
    require('includes/connect_db.php');
    require('includes/header.php');
    validateAdminAccess();
    
    if(!isset($_POST['title'])){
        header("Location: feed.php");
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
        $title = $_POST['title'];
        $content= $_POST['content'];
        $category = $_POST['category'];
    ?>
        
        <div class='suggestion-box'>
            <div class="content-box">
            <p class='suggestion-category'><?php echo $category; ?></p>
            <br>
            <p class='suggestion-title'><?php echo Title . ": " . $title ?></p>
            <p class='suggestion-content'><?php echo Suggestion . ": ". $content; ?></p>
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
        	<textarea required id="feedback" placeholder="What do you feel about this suggestion?" class="form-control" name="suggestion" type="text" value="" rows="7" ></textarea><br />	
            
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Implement Suggestion
                </label>
            </div><br />
            
            
            
            <a class="btn btn-danger" href="feed.php" role="button">Cancel</a>
            <input id="feedback_btn" class="btn btn-primary" type="submit" value="Submit Feedback!">
        </form>
    </body>
</html>