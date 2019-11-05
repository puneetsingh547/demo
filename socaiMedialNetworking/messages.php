<?php
include("includes/header.php");
// include("includes/classes/Message.php");
// include("includes/classes/User.php");

$message_obj = new Message($conn, $userLogeedIn);

if(isset($_GET['u'])){
    $user_to = $_GET['u'];
}
else{
    $user_to = $message_obj->getMostRecentUser();
    if($user_to == false)
        $user_to = 'new';
}
if($user_to != "new")
    $user_to_obj = new User($conn, $user_to);

if(isset($_POST['post_message'])){
    if(isset($_POST['message_body'])){
        $body = mysqli_escape_string($conn, $_POST['message_body']);
        $date = date("Y-m-d H:i:s");
        $message_obj->sendMessage($user_to, $body, $date);
    }
}

?>
    <div class="user-details column" > 
        <a href="<?php echo $user['username']; ?>"><img src="<?php echo $user['profile_pic']; ?>" alt=""></a>
        <div class="user-detail-left-right">
            
            <a href="<?php echo $user['username']; ?>"><?php echo $user['first_name']." ".$user['last_name']."<br>" ; ?></a>
            <?php echo "Posts: ".$user['num_posts']."<br>"; 
            echo "Likes: ".$user['num_likes']; ?>
        </div>
    </div>
    <div class="main-column column" id="main_column" style="height:220px;">
        <?php
            if($user_to !="new"){
                echo "<h4>You and <a href='$user_to'>".$user_to_obj->getFirstAndLastName()."</a></h4><hr><br>";
                echo "<div class='loaded_message' id='scroll_message'>";
                echo $message_obj->getMessage($user_to);
                echo "</div>";
            }
            else{
                echo "<h4 style='font-size: 26px;padding: 10 0 20 0;'>New Message</h4>";
            }
        ?>
        <div class="message_post">
            <form action="" method="POST">
                <?php
                    if($user_to == "new"){
                        echo "Select the friend you would like to message<br><br>";
                    ?>
                       <span style="font-size: 15.5px;">To : </span> <input type='text' onkeyup='getUsers(this.value," <?php echo $userLogeedIn; ?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>
                    <?php
                        echo "<div class='results'><div>";
                    }
                    else{
                        echo "<textarea name='message_body' id='message_textarea' placeholder='Write Your Message...'></textarea>";
                        echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
                    }
                ?>
            </form>
        </div>
        
    </div>
    <script>
            var div = document.getElementById("scroll_message");
            div.scrollTop = div.scrollHeight();
        </script>
    </div>
    </div>
    <div class="user-details column" id="conversation">
            <h4>Conversation</h4>

            <div class="loaded_conversation">
                <?php echo $message_obj->getConvos(); ?>
            </div><br>
            <a href="messages.php?u=new" >New Message</a>

    </div>