<html>
    <?php
        require("includes/connect_db.php");
        require("includes/header.php");
        
        $suggestionData = getTrending();
    ?>
    <body>
        <br />
        <div id="search-box" class="input-group">
          <input type="text" id = 'search-bar' class="form-control" placeholder="Search for...">
          <span class="input-group-btn">
            <button id="search-btn" class="btn btn-default" type="button">Search</button>
          </span>
        </div>
        
        <h3 id="msg-sub">Vote to help suggest!</h3>
        <div id="suggestion-container">
            <?php
            while( $row = mysqli_fetch_array( $suggestionData, MYSQLI_ASSOC ) ){
            ?>
                <div class="suggestion-box" data-title="<?= $row['title'] ?>" data-content="<?= $row['content'] ?>" data-fname="<?= $row['fname'] ?>" data-lname="<?= $row['lname'] ?>" data-postdate="<?= $row['post_date'] ?>">
                    <div class="sugg-border-box">
                        <div class="rating-box">
                            <p class="up-rating" data-id="<?= $row['id'] ?>"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <num><?php echo $row['up']; ?></num></p>
                            <p class="down-rating" data-id="<?= $row['id'] ?>"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> <num><?php echo $row['down']; ?></num></p>
                        </div>
                        
                        <div class="content-box">
                            <p class="suggestion-title"><?php echo $row['title']; ?></p>
                            <p class="suggestion-content">
                                <?php
                                if(strlen ($row['content']) > 40)
                                    echo substr($row['content'], 0, 40) . "...";
                                else
                                    echo $row['content'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php  
            }
            ?> 
        </div>
        <div id="contribute-btn">
            <div id="suggest-btn" class="btn-group btn-group-lg" role="group">
                <a class="btn btn-default" href="new_suggestion.php" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
            </div>
        </div>
        
        <div id="details-pane">
            <div id="details-border">
                <div id="details-box">
                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h1 id="title">Title</h1>
                    <h2 id="subtitle">By <span id="contributer">Fname Lname</span> <span id="postdate">Date</span></h2>
                    <p id="content">Content</p>
                </div>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">

    $('#search-btn').click(function() {
        var rex = new RegExp($(this).parent().prev().val(), 'i');

        $(".suggestion-box").hide();
        $(".suggestion-box").filter(function (){
            return rex.test($(this).data("content")) || rex.test($(this).data("title"));
        }).show();
    });

    $(".suggestion-box").click(function(){
        var title = $(this).find(".suggestion-title").text();
        var date = $(this).data("postdate");
        var fname = $(this).data("fname");
        var lname = $(this).data("lname");
        var content = $(this).data("content");
        
        $("#details-box #title").text(title);
        $("#details-box #contributer").text((fname != "" || lname != "") ? fname + " " + lname : "anonymous");
        $("#details-box #postdate").text(date);
        $("#details-box #content").text(content);
        
        $("#details-pane").show();
    });
    
    $("#details-border").click(function(e){
        e.stopPropagation();
    })
    
    $("#details-box .close, #details-pane").click(function(){
        $("#details-pane").hide();
    });

    $(".up-rating").click(function(e){
        var rating = new Array();
        rating['id'] = $(this).data("id");
        rating['num'] = $(this).find("num");
        
        $.ajax({
            type: "POST",
            url: "includes/rating.php",
            data: {
                id: rating['id'],
                up: true
            },
            dataType: "text",
            success: function(data){
                if(data == "login"){
                    alert("You need to log in to vote");
                } else if (data == "duplicate"){ 
                    alert("You already voted here");
                } else {
                    rating['num'].text(data);
                }
            }
        });
        e.stopPropagation();
    });
    
    $(".down-rating").click(function(e){
        var rating = new Array();
        rating['id'] = $(this).data("id");
        rating['num'] = $(this).find("num");
        
        $.ajax({
            type: "POST",
            url: "includes/rating.php",
            data: {
                id: rating['id'],
                down: true
            },
            dataType: "text",
            success: function(data){
                if(data == "login"){
                    alert("You need to log in to vote");
                } else if (data == "duplicate"){ 
                    alert("You already voted here");
                } else {
                    rating['num'].text(data);
                }
            }
        });
        
        e.stopPropagation();
    });
</script>