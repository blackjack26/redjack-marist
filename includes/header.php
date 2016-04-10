<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/admin.css" type="text/css" />
    <link rel="stylesheet" href="css/header.css" type="text/css" />
    <link rel="stylesheet" href="css/main.css" type="text/css" />
    <link rel="stylesheet" href="css/feed.css" type="text/css" />
    <link rel="stylesheet" href="css/feedback.css" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans:700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <title>The Better Marist</title>
</head>
<div id= "headWrapper">
    <?php
    require_once('includes/connect_db.php');
    require('includes/helpers.php');
    if(basename($_SERVER['PHP_SELF']) != "login.php"){
        if(!isLoggedIn()){
            echo '<a id="signIn" href="login.php">Log in</a>';
        } else {
            echo '<a id="signOut" href="login.php?logout=true">Log Out</a>';
        }
    }
    ?>
    <ul id="mobile-menu" class="nav nav-pills">
        <li role="presentation" class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="glyphicon glyphicon-menu-hamburger burg" aria-hidden="true"></span><span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li <?php if(basename($_SERVER['PHP_SELF']) == "index.php") echo 'class="disabled"' ?>><a href="index.php">Home</a></li>
                <li <?php if(basename($_SERVER['PHP_SELF']) == "trending.php") echo 'class="disabled"' ?>><a href="trending.php">Trending</a></li>
                <li <?php if(basename($_SERVER['PHP_SELF']) == "new_suggestion.php") echo 'class="disabled"' ?>><a href="new_suggestion.php">Suggest</a></li>
                <?php if(hasAdminAccess()) { ?><li <?php if(basename($_SERVER['PHP_SELF']) == "feed.php") echo 'class="disabled"' ?>><a href="feed.php">Feedback</a></li><?php } ?>
                <?php if(hasAdminAccess()) { ?><li <?php if(basename($_SERVER['PHP_SELF']) == "statistics.php") echo 'class="disabled"' ?>><a href="statistics.php">Statistics</a></li><?php } ?>
                
                <?php if(hasAdminAccess() && getUserDataByUsername($_SESSION['admin'])['division'] == "") { ?>
                    <li role="separator" class="divider"></li>
                    <li <?php if(basename($_SERVER['PHP_SELF']) == "admin.php") echo 'class="disabled"' ?>><a href="admin.php">Admin</a></li>
                <?php } ?>
            </ul>
        </li>
    </ul>
</div>