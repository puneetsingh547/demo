<style></style>
<?php
    include("includes/header.php");

    if(isset($_GET['profile_username'])){
        $username = $_GET['profile_username'];

        $user_detail_query = mysqli_query($con , "SELECT * from users where username='$username'");
        $user_array = mysqli_fetch_array($user_detail_query);

        $num_friends = (substr_count($user_array['friend_array'], ","))-1;
    }

    if(isset($_POST['removed_friend'])){
        $user = new User($con ,$userLoggedIn);
        $user->removeFreind($username);
        
    }
    if(isset($_POST['add_friend'])){
        $user = new User($con , $userLoggedIn);
        $user->sendRequest($username);
    }
    if(isset($_POST['respond_request'])){
        header("Location: request.php");
    }
?>
<style>
.wrapper {
    margin-left: 0;
    padding-left: 0;
}
</style>
    <div class="profile_left">
            <img src="<?php echo $user_array['profile_pic']; ?>" alt="">
        
            <div class="profile_info">
                <p><?php echo "Posts ". $user_array['num_posts']; ?></p>
                <p><?php echo "Likes ". $user_array['num_likes']; ?></p>
                <p><?php echo "Friends ". $num_friends; ?></p>
            </div>
            <form action="<?php echo $username; ?>" method='POST'>
                <?php 
                $profile_user_obj = new User($con, $username);
                if($profile_user_obj->isClosed()){
                    header("Loaction : user_closed.php");
                }
                $logged_in_user_obj = new User($con , $userLoggedIn);

                if($userLoggedIn != $username){
                   
                    if($logged_in_user_obj->isFriend($username)){
                        echo "<input type='submit' name='removed_friend' value='Remove Friend' class='danger'><br>";
                    }
                    else if($logged_in_user_obj->didReciveRequest($username)){
                        echo "<input type='submit' name='respond_request' value='Respnod to Request' class='warning'><br>";
                    }
                    else if($logged_in_user_obj->didSendRequest($username)){
                        echo "<input type='submit' name='' value='Request Sent' class='default'><br>";
                    }
                    else {
                        echo "<input type='submit' name='add_friend' value='Add Friend' class='success'><br>";
                    }
                }

                
                ?>
                
            </form>
            <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something"> 

            <?php 
            if($userLoggedIn != $username){
            echo "<div class='profile_info_button'>";
                echo $logged_in_user_obj->getMetualFriends($username). " Mutual Friends";
            echo "</div>";
            }
            ?>

        </div>
    <div class="profile_main_column column"><!-- Button trigger modal -->
    <div class="post_area"></div>
    <p id="loading">loading...</p>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="postModalLabel">Post Something</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
             <p>This will appear on user's profile page and also their newsfeeds for your friends to show!</p>

             <form action="" method='POST' class="profile_post">
                <div class="form-group">
                    <textarea name="post_body" class="form-control"></textarea>
                    <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                    <input type="hidden" name="user_to" value="<?php echo $username; ?>">
                </div>

             </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
        </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function(){
            
        var userLoggedIn = "<?php echo $userLoggedIn; ?>";
        var profileUsername = "<?php echo $username; ?>";
        $("#loading").show();

        $.ajax({
            url:"includes/handlers/ajax_profile_load_post.php",
            type:"POST",
            data:"page=1&userLoggedIn="+userLoggedIn +"&profileUsername="+ profileUsername,
            cache:false,

            success:function(data){
                $("#loading").hide();
                $(".post_area").html(data);
                
            }
        });
        $(window).scroll(function(){
            var height = $(".post_area").height();
            var scroll_top = $(this).scrollTop();
            var page = $(".post_area").find(".nextPage").val();
            var noMorePosts = $(".post_area").find(".noMorePosts").val();
            
            if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) || noMorePosts == 'false'){
               
                     $("#loading").show();

                    var ajaxReq = $.ajax({
                        url:"includes/handlers/ajax_profile_load_post.php",
                        type:"POST",
                        data:"page=" + page + "&userLoggedIn="+userLoggedIn+"&profileUsername="+ profileUsername,
                        cache:false,

                        success:function(response){
                            $(".post_area").find(".nextPage").remove();
                            $(".post_area").find(".noMorePosts").remove();

                            $("#loading").hide();
                            $(".post_area").append(response);
                            
                        }
                    });
            }
            return false;
        });
    });


    </script>
    </div>
</body>
</html>