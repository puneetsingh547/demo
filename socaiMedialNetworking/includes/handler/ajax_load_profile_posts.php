<?php
    include("../../config/config.php");
    include("../classes/User.php");
    include("../classes/Post.php");

    $limit=10;  //no of post to be looded per call

    $posts = new Post($conn, $_REQUEST['userLoggedIn']);
    $posts->loadProfilePosts($_REQUEST, $limit);

?>