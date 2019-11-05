<html>
    <head>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <style>
            body{
                background-color:#fff;
                overflow:hidden;
                font-family:sans-serif;
            }
            form{
                position:absolute;
                top:3px;
            }
        </style>
        <?php
            require 'config/config.php';
            include("includes/classes/User.php");
            include("includes/classes/Post.php");
            include("includes/classes/Notification.php");


            if(isset($_SESSION['username'])){
                $userLogeedIn = $_SESSION['username'];
                $user_detail_query = mysqli_query($conn, "SELECT * FROM users where username='$userLogeedIn'");
                $user = mysqli_fetch_array($user_detail_query);
                $total_user_likes = $user['num_likes'];
                
            }
            else{
                header("location: register.php");
            }
            if(isset($_GET['post_id'])){
                $post_id = $_GET['post_id'];
            }

            $gat_likes = mysqli_query($conn, "SELECT likes, added_by from posts where id='$post_id'");
            $row = mysqli_fetch_array($gat_likes);
            $total_likes = $row['likes'];
            $user_liked = $row['added_by'];

            $user_detail_query = mysqli_query($conn, "SELECT * from users where username='$user_liked'");
            $row = mysqli_fetch_array($user_detail_query);

            //like button
            if(isset($_POST['like_button'])){
                $total_likes++;
                $query = mysqli_query($conn, "UPDATE posts set likes='$total_likes' where id='$post_id'");
                $total_user_likes++;
                $user_likes = mysqli_query($conn, "UPDATE users set num_likes='$total_user_likes' where username='$user_liked'");
                $insert_user = mysqli_query($conn, "INSERT into likes(username, post_id) values('$userLogeedIn', $post_id) ");
                //insert notification
                if($user_liked != $userLogeedIn){
                    $notification = new Notification($conn, $userLogeedIn);
                    $notification->insertNotification($post_id, $user_liked, "like");
                }
            }
            //unlike button
            if(isset($_POST['unlike_button'])){
                $total_likes--;
                $query = mysqli_query($conn, "UPDATE posts set likes='$total_likes' where id='$post_id'");
                $total_user_likes--;
                $user_likes = mysqli_query($conn, "UPDATE users set num_likes='$total_user_likes' where username='$user_liked'");
                $insert_user = mysqli_query($conn, "DELETE FROM likes where username='$userLogeedIn' and post_id='$post_id'");
            }

            //check for previous query
            $check_query = mysqli_query($conn, "SELECT * FROM likes where username='$userLogeedIn' and post_id='$post_id'");
            $num_rows = mysqli_num_rows($check_query);

            if($num_rows > 0){
                echo '<form action=like.php?post_id=' .$post_id. ' method="POST">
                    <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
                    <div class="like_value">
                        &nbsp;'. $total_likes .' Likes
                    </div>
                </form>';
            }
            else {
                echo '<form action=like.php?post_id=' .$post_id. ' method="POST">
                    <input type="submit" class="comment_like" name="like_button" value="Like">
                    <div class="like_value">
                        '. $total_likes .' Likes
                    </div>
                </form>';
            }
        ?>
    </body>
</html>