<html>
    <?php
        require('includes/header.php');
        require("includes/connect_db.php");
        validateAdminAccess();
    ?>
    <body> 
    <?php
        $suggestions = getOldestSuggestions();
        while( $row = mysqli_fetch_array($suggestions, MYSQLI_ASSOC ) ){
        ?>
            <div class="suggestion-box" data-id="<?= $row['id'] ?>" data-title="<?= $row['title'] ?>" data-content="<?= $row['content'] ?>" data-category="<?= $row['category'] ?>">
                <p class="suggestion-category"> <?php echo $row['category']; ?> </p>
                <br>
                <p class="suggestion-title"><?php echo Title . ":" . " " . $row['title']; ?></p>
                <p class="suggestion-content">
                    <?php 
                    if(strlen ($row['content']) > 40)
                        echo "Suggestion: " . substr($row['content'], 0, 40) . "...";
                    else
                        echo "Suggestion: " . $row['content'];
                    ?> 
                </p> 
               
               <?php 
                if($row['reviewed'] == 0){
                    echo '<p id="needs-review" class = "glyphicon glyphicon-exclamation-sign"> </p>';
                } else if( $row['implemented'] == 0){
                    echo '<p id="not-implemented" class= "glyphicon glyphicon-remove-circle"></p>';
                } else{
                    echo '<p id="implemented" class= "glyphicon glyphicon-ok-circle"> </p>';
                }
               ?>
            </div>
        <?php  
        }
        ?> 
    </body>
    <script>
    
        $(".suggestion-box").click(function(){
            post("feedback.php", { 
                id: $(this).data("id"),
                title: $(this).data("title"),
                content: $(this).data("content"),
                category: $(this).data("category")
            }); 
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