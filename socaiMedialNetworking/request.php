<?php
    include("includes/header.php");
    // include("includes/classes/User.php");
    // include("includes/classes/Post.php");

?>
<div class="main_column column" id="main_column">
    <h4>Friends Requests</h4>
    <?php
        $query = mysqli_query($conn, "SELECT * from friend_request where user_to='$userLogeedIn'");
        if(mysqli_num_rows($query) == 0){
            echo "You have no friend request at this time.";
        }
        else{
            while($row=mysqli_fetch_array($query)){
                $user_from = $row['user_from'];
                $user_from_obj = new User($conn, $user_from);

                echo $user_from_obj->getFirstAndLastName()."  Sent you a friend request!";

                $user_from_friend_array = $user_from_obj->getFriendArray();

                if(isset($_POST['accept_reqest' . $user_from])){
                    $add_friend_array = mysqli_query($conn, "UPDATE users set friend_array=CONCAT(friend_array, '$user_from,') where username='$userLogeedIn'");
                    $add_friend_array = mysqli_query($conn, "UPDATE users set friend_array=CONCAT(friend_array, '$userLogeedIn,') where username='$user_from'");

                    $delete_query = mysqli_query($conn, "DELETE from friend_request where user_to='$userLogeedIn' and user_from='$user_from'");
                    echo "You are friends now!";
                    header("location: request.php");
                }
                if(isset($_POST['ignore_reqest' . $user_from])){
                    $delete_query = mysqli_query($conn, "DELETE from friend_request where user_to='$userLogeedIn' and user_from='$user_from'");
                    echo "Request Ignored!";
                    header("location: request.php");
                }
                ?>
                <form action="request.php" method="POST">
                    <input type="submit" name="accept_reqest<?php echo $user_from; ?>" id="accept_button" value="Accept">
                    <input type="submit" name="ignore_reqest<?php echo $user_from; ?>" id="ignore_button" value="Ignore">
                </form>


                <?php
            }
        }
    ?>
    
</div>