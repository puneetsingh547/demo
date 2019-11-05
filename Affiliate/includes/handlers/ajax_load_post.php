<?php
    include("../../config/config.php");
    include("../classes/Post.php");
    include("../classes/User.php");

    $limit = 10;
    $post_obj = new Post($con , $_REQUEST['userLoggedIn']);
    $post_obj->loadPostsFriends($_REQUEST , $limit);
   
?>