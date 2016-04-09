<html>
    <?php
        require("includes/header.php");
    ?>
    <body>
        <h3 class="form_header">New Suggestion<br><small>Information</small></h3>
        <form id="suggest_form" action="new_suggestion2.php" method="POST" enctype="multipart/form-data">
        	<div class="form-group">
        	    <label for="title">Title*</label><br />
        		<input required id="title" placeholder="Title" class="form-control" name="title" type="text" value="" size="30" /><br />
        		
        	    <label for="category">Category*</label><br />
        		<select name="category" class="form-control">
                    <option>Student Life</option>
                    <option>Administration</option>
                    <option>Academics</option>
                    <option>Athletics</option>
                    <option>Housing</option>
                    <option>Safety</option>
                    <option>Other</option>
                </select><br />
        		
        		<label for="suggestion">Suggestion*</label><br />
        		<textarea required id="suggestion" placeholder="This could be so much better..." class="form-control" name="suggestion" type="text" value="" rows="7" ></textarea><br />
        
                <!-- Used to prevent going to 2nd page through URL -->
                <input type="hidden" name="first_page" value="true" />
                
                <a class="btn btn-danger" href="index.php" role="button">Cancel</a>
        	    <input id="sugg_cont_btn" class="btn btn-default" type="submit" value="Continue!">
        	</div>
        </form>	
    </body>
</html>