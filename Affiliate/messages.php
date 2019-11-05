<?php
include("includes/header.php");

$message_obj = new Messages($con , $userLoggedIn);

if(isset($_GET['u']))
    $user_to = $_GET['u'];
else{
    $user_to = $message_obj->getMostRecentPost();
    if($user_to == false){
        $user_to = 'new';
    }
    
}
if($user_to != 'new')
        $user_to_obj = new User($con , $user_to);

if(isset($_POST['post_message'])){
    if(isset($_POST['message_body'])){
        $string = mysqli_real_escape_string($_POST['message_body']);
        $date = date("Y-m-d H:i:s");
        $message_obj->sendMessage($user_to , $body , $date);
    }
}

?>
<div class= "user_details column">
    <img src="<?php echo $user['profile_pic']; ?>" alt="">
    <div class="user_details_left_right">
        <a href="<?php echo $userLoggedIn; ?>">
        <?php
        echo $user['first_name'] . " " . $user['last_name'] ."<br>";
        ?></a>
        <?php
        echo "Posts : " . $user["num_posts"] . "<br>";
        echo "Likes : " . $user["num_likes"] . "<br>";
        ?>
    </div>
</div>
<div class="main_column column" id="main_column">
    <?php
        echo "<h4>You and <a href='$user_to'>".$user_to_obj->getFirstAndLastName()."</a></h4>";
    ?>
    <div class="loaded_messages">
        <form action="" method="POST">
            <?php
            if($user_to == 'new'){
                echo "Select The friend you would like to message <br><br>";
                echo "To <input type='text' onkeyup='getUser(this.value , $userLoggedIn)' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>";
                echo "<div class='results'></div>";
            }
            else{
                echo "<textarea class='message_body' id='message_textarea' placeholder='Write your messages ...'></textarea>";
                echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
            }
            
            ?>
        
        </form>
    </div>

</div>