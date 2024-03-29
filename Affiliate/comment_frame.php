<?php
    require "config/config.php";
    include("includes/classes/User.php");
    include("includes/classes/Post.php");   
     if(isset($_SESSION['username'])){
        $userLoggedIn = $_SESSION['username'];
        
        $get_user_detals = mysqli_query($con , "SELECT * FROM users where username='$userLoggedIn'");
        $user = mysqli_fetch_array($get_user_detals);
        $get_comment_details = mysqli_query($con , "SELECT * from ");
    }
    else{
        header("location: register.php");
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
    <style>
        *{
            font-size:12px;
            font-style:Arial, sans-siref;
        }
    </style>
        <script>
            function toggle(){
                var element = document.getElementById("comment_section");
                if(element.style.display == "block")
                    element.style.display = "none";
                else
                    element.style.display = "block";
            }
        </script>

        <?php
            //get id of post
            
            if(isset($_GET['post_id'])){
                $post_id = $_GET['post_id'];
                
            }
            $user_query = mysqli_query($con , "SELECT added_by , user_to from posts where id= $post_id");
            $row = mysqli_fetch_array($user_query);
            
            $posted_to = $row['added_by'];

            if(isset($_POST['postComment' . $post_id])){
                $post_body = $_POST['post_body'];
                $post_body = mysqli_escape_string($con , $post_body);
                $date_time_now = date("Y-m-d H:i:s");
                
                $insert_post = mysqli_query($con , "INSERT into comments (post_body , posted_by , posted_to , date_added , removed ,post_id) values('$post_body', '$userLoggedIn', '$posted_to','$date_time_now', 'no' , $post_id) ");
                echo "<p>Comment posted!</p>";
            }
        ?>
        <form action="comment_frame.php?post_id=<?php echo $post_id ;?>" name="postComment<?php echo $post_id; ?>" id="comment_section" method="POST" >
            <textarea name="post_body"></textarea>
            <input type="submit" name="postComment<?php echo $post_id; ?>" vlaue="Post">
        </form>
        <!-- Load Comment -->
        <?php 
        $check_comment = mysqli_query($con, "SELECT * from comments where post_id='$post_id' order by id asc");
        $count = mysqli_num_rows($check_comment);
       
        if($count != 0 ){
            while($comment = mysqli_fetch_array($check_comment)){
               $comment_body = $comment['post_body'];
               $posted_by = $comment['posted_by'];
               $posted_to = $comment['posted_to'];
               $date_added = $comment['date_added'];
               $removed = $comment['removed'];

               //time frame
               $date_time_now = date("Y-m-d H:i:s");
               $start_date = new DateTime($date_added);
               $end_date = new DateTime($date_time_now);

               $interval = $start_date->diff($end_date);

               if($interval->y >= 1){
                   if($interval == 1){
                       $time_message = $interval->y ."year ago";
                   }
                   else {
                       $time_message = $interval->y . "years ago";
                   }
               }
               else if($interval->m >= 1){
                   if($interval->d == 0 ){
                       $days = "ago";
                   }
                   else if($interval->d == 1){
                       $days = $interval->d ."day ago";
                   }
                   else {
                       $days = $interval->d . "days ago";
                   }

                   if($interval->m == 1){
                       $time_message = $interval->m . " month ".$days;
                   }
                   else{
                       $time_message = $interval->m . " months ".$days;
                   }

               }
               else if($interval->d >= 1){
                   if($interval->d == 1){
                       $time_message = "yesterday";
                   }
                   else {
                       $time_message = $interval->d . "days ago";
                   }
               }
               else if($interval->h >= 1){
                   if($interval->h == 1){
                       $time_message = $interval->h."hour";
                   }
                   else {
                       $time_message = $interval->h . "hours ago";
                   }
               }
               else if($interval->i >= 1){
                   if($interval->i == 1){
                       $time_message = $interval->i."minute";
                   }
                   else {
                       $time_message = $interval->i . "minutes ago";
                   }
               }
               else {
                   if($interval->s < 30){
                       $time_message = "Just now";
                   }
                   else {
                       $time_message = $interval->s . "second ago";
                   }
               }
               $user_obj = new User($con , $posted_by);

               ?>
               
               <div class="comment_section">
                    <a href="<?php echo $posted_by; ?>" target="_parent" > <img src="<?php echo $user_obj->getProfilePic(); ?>" alt="" title="<?php echo $posted_by ?>" style="float:left; height:30px;"></a>
                    <a href="<?php echo $posted_by; ?>" target="_parent" ><b><?php echo $user_obj->getFirstAndLastName(); ?></b></a>
                    &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . " <br> " . $comment_body; ?>
                    <hr>
                </div>
                <?php
            }
        }
        else{
            echo "<div><br><br>No Comments to show !</div>";
        }
        ?>
        

        
    </body>
</html>