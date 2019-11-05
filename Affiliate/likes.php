<html>
    <head>
    <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>

    <style>
        * {
            font-family : sans-serif;
        }
        body {
            background-color: #fff;
        }
         form {
            top: 4px;
            position: absolute;
        }

    </style>

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

    //get id of post
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
         
    }

    $get_likes = mysqli_query($con , "SELECT likes , added_by from posts where id='$post_id'");
    $row = mysqli_fetch_array($get_likes);
    $total_likes = $row['likes'];
    $user_liked = $row['added_by'];
    
    $user_details_query = mysqli_query($con, "SELECT * FROM users where username='$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    $total_user_likes = $row['num_likes'];

    //liked button
    if(isset($_POST['like_button'])){
        $total_likes++;
        $query = mysqli_query($con , "UPDATE posts set likes='$total_likes' where id='$post_id'");
        $total_user_likes++;
        $user_likes = mysqli_query($con , "UPDATE users set num_likes='$total_user_likes' where username='$user_liked'");
        $insert_users = mysqli_query($con , "INSERT into likes (username , post_id) values ('$userLoggedIn', '$post_id' )");
       
        //Notification
    }

    //unlike button
    if(isset($_POST['unlike_button'])){
        $total_likes--;
        $query = mysqli_query($con , "UPDATE posts set likes='$total_likes' where id='$post_id'");
        $total_user_likes--;
        $user_likes = mysqli_query($con , "UPDATE users set num_likes='$total_user_likes' where username='$user_liked'");
        $insert_users = mysqli_query($con , "DELETE FROM likes where username='$userLoggedIn' and post_id='$post_id'");

    }
    //check for previous likes
    
    $check_query = mysqli_query($con , "SELECT * FROM likes where username='$userLoggedIn' and post_id='$post_id'");
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows > 0){
        echo '<form action="likes.php?post_id='.$post_id.'" method="POST">
            <input type="submit" value=Unlikes name="unlike_button" class="unlikes_button">
            <div class="like_values">
                '. $total_likes .' Likes
            </div>
        </form>
        ' ;
    }
    else {
        echo '<form action="likes.php?post_id='.$post_id.'" method="POST">
        <input type="submit" value=Likes name="like_button" class="like_button">
        <div class="like_values">
            '. $total_likes .' Likes
        </div>
    </form>
    ' ;
}
    
?>
    </body>
</html>