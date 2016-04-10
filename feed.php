<html>
    <?php
        require('includes/header.php');
        require("includes/connect_db.php");
        validateAdminAccess();
    ?>
    <body>
        <div id="filter-btn-container">
            <div id="filter-btns" class="btn-group" role="group" aria-label="...">
              <a href="feed.php" role="button" class="btn btn-default <?php echo !isset($_GET['filter']) ? "active" : ""; ?>">
                  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span id="t">&nbsp;&nbsp;None</span>
              </a>
              <a href="feed.php?filter=notreviewed" role="button" class="btn btn-default <?php echo ($_GET['filter'] == "notreviewed") ? "active" : ""; ?>">
                  <span id="needs-review-btn" class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span id="t">&nbsp;&nbsp;Not Reviewed</span>
              </a>
              <a href="feed.php?filter=unimplemented" role="button" class="btn btn-default <?php echo ($_GET['filter'] == "unimplemented") ? "active" : ""; ?>">
                  <span id="not-implemented-btn" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span id="t">&nbsp;&nbsp;Not Implemented</span>
              </a>
              <a href="feed.php?filter=implemented" role="button" class="btn btn-default <?php echo ($_GET['filter'] == "implemented") ? "active" : ""; ?>">
                  <span id="implemented-btn" class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span><span id="t">&nbsp;&nbsp;Implemented</span>
              </a>
            </div> 
        </div>
    <?php
        $suggestions = getOldestSuggestions();
    
        if(isset($_GET['filter'])){
            if($_GET['filter'] == "implemented"){
                $suggestions = getRecords("suggestions", "implemented", "1");
            } else if($_GET['filter'] == "unimplemented"){
                $suggestions = getQuery("SELECT * FROM suggestions WHERE implemented = 0 AND reviewed = 1");
            } else if($_GET['filter'] == "notreviewed"){
                $suggestions = getQuery("SELECT * FROM suggestions WHERE reviewed = 0");
            }
        }
        
        $userDivision = getUserDataByUsername($_SESSION['admin'])['division'];
        
        while( $row = mysqli_fetch_array($suggestions, MYSQLI_ASSOC ) ){
            if($userDivision == "" || $row['category'] == $userDivision || $row['category'] == "Other"){
        ?>
            <div style="<?php if($row['duplicate'] == 1) echo 'background-color:rgba(210,210,210,0.6);'; ?>" class="suggestion-box" data-im="<?= $row['implemented'] ?>" data-id="<?= $row['id'] ?>" data-title="<?= $row['title'] ?>" data-content="<?= $row['content'] ?>" data-category="<?= $row['category'] ?>">
                
                <p style="font-style:italic" class="suggestion-category"> <?php echo $row['category']; ?> </p>
                <br>
                <p class="suggestion-title"><b>Title: </b><?php echo $row['title']; ?></p>
                <p class="suggestion-content">
                    <?php 
                    if(strlen ($row['content']) > 40)
                        echo "<b>Suggestion:</b> " . substr($row['content'], 0, 40) . "...";
                    else
                        echo "<b>Suggestion:</b> " . $row['content'];
                    ?> 
                </p> 
               
               <?php 
                if($row['reviewed'] == 0){
                    echo '<p id="needs-review"><span id="needs-review-btn" class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Not Reviewed</p>';
                } else if( $row['implemented'] == 0){
                    
                    if($row['duplicate'] == 1)
                        echo '<p id="not-implemented"><span id="not-implemented-btn" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Not Implemented (Duplicate)</p>';
                    else {
                        echo '<p id="not-implemented"><span id="not-implemented-btn" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Not Implemented</p>';
                    }
                } else{
                    echo '<p id="implemented"><span id="implemented-btn" class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Implemented</p>';
                }
               ?>
                <p class="post-date" style="display:inline-block; float:left; font-size:14px; font-style:italic"><?php echo $row['post_date']; ?></p>
                <p style="display:inline-block; font-size: 14px; float:right" class="down-rating"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <num><?php echo $row['down']; ?></num></p>
                <p style="display:inline-block; font-size: 14px; float:right" class="up-rating"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <num><?php echo $row['up']; ?></num></p>
                
            </div>
        <?php  
            }
        }
        ?> 
        <div id="details-pane">
            <div id="details-border">
                <div id="details-box">
                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h1 style="text-align:center">Feedback</h1>
                    <h1 id="title">Title</h1>
                    <h2 id="subtitle">By <span id="contributer">Fname Lname</span> <span id="postdate">Date</span></h2>
                    <p id="content">Content</p>
                </div>
            </div>
        </div>
    </body>
    <script>
    
        $(".suggestion-box").click(function(){
            if($(this).find("#needs-review").length <= 0){
                
                var suggestionId = $(this).data("id");
                var implemented = $(this).data("im") == "1";
                $.ajax({
                    type: "POST",
                    url: "includes/get_feedback.php",
                    data: {
                        id: suggestionId
                    },
                    dataType: "text",
                    success: function(data){
                        data = JSON.parse(data);
                        $("#details-box #not-implemented").remove();
                        $("#details-box #implemented").remove();
                        $("#details-box #title").text(data['title']);
                        $("#details-box #contributer").text(data['username']);
                        $("#details-box #postdate").text(data['post_date']);
                        $("#details-box #content").text(data['content']);

                        if(implemented){
                            $("#details-box").append('<p id="implemented"><span id="implemented-btn" class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Implemented</p>');
                        } else {
                            $("#details-box").append('<p id="not-implemented"><span id="not-implemented-btn" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Not Implemented</p>');
                        }
                        
                        $("#details-pane").show();
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
                
            }
        });
        
        $("#details-border").click(function(e){
            e.stopPropagation();
        })
        
        $("#details-box .close, #details-pane").click(function(){
            $("#details-pane").hide();
        });
    
        $(".suggestion-box").click(function(){
            if($(this).find("#needs-review").length > 0){
                post("feedback.php", { 
                    id: $(this).data("id"),
                    title: $(this).data("title"),
                    content: $(this).data("content"),
                    category: $(this).data("category")
                }); 
            }
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