<?php
    include("includes/header.php");
    // include("includes/classes/User.php");
    // include("includes/classes/Post.php");
    // include("includes/classes/Message.php");
   
    if(isset($_GET['profile_username'])){
        $username = $_GET['profile_username'];
        $user_detail_query = mysqli_query($conn, "SELECT * from users where username='$username'");
        $user_array = mysqli_fetch_array($user_detail_query);

        $num_friends = (substr_count($user_array['friend_array'],",")) - 1;
    }
    if(isset($_POST['remove_friend'])){
        $user = new User($conn, $userLogeedIn);
        $user->removeFriend($username);
    }
    if(isset($_POST['add_friend'])){
        $user = new User($conn, $userLogeedIn);
        $user->sendRequest($username);
    }
    if(isset($_POST['respond_request'])){
        header("location: request.php");
    }
    $message_obj = new Message($conn, $userLogeedIn);
    if(isset($_POST['post_message'])){
        
        if(isset($_POST['message_body'])){
            $body = mysqli_escape_string($conn, $_POST['message_body']);
            $date = date("Y-m-d H:i:s");
            $message_obj->sendMessage($username, $body, $date);
        }
    }

    $link = '#profileTabs a[href="#message_div"]';
    echo "<script>
            $(function(){
                $('".$link."').tab('show');
            });
        </script>";
?>

            <style>
                .wrapper {
                    margin-left: 0;
                    padding-left: 0;
                }
            </style>
 
            <div class="profile_left">
                <img src="<?php echo $user_array['profile_pic']; ?>">

                <div class="profile_info">
                    <p><?php echo "Posts: " .$user_array['num_posts']; ?></p>
                    <p><?php echo "Likes: " .$user_array['num_likes']; ?></p>
                    <p><?php echo "Friends: " .$num_friends; ?></p>
                </div>

                <form action="<?php echo $username; ?>" method="POST">
                    <?php
                        $profile_user_obj = new User($conn, $username);
                        if($profile_user_obj->isClosed()){
                            headder("location:user_closed.php");
                        }
                        $logged_in_user_obj = new User($conn, $userLogeedIn);

                        if($userLogeedIn != $username){
                            if($logged_in_user_obj->isFriend($username)){
                                echo '<input type="submit" name="remove_friend" class="denger" value="Remove Friend"><br>';
                            }
                            else if($logged_in_user_obj->didRecivedRequest($username)){
                                echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
                            }
                            else if($logged_in_user_obj->didSendRequest($username)){
                                echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
                            }
                            else
                                echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
                        }
                    ?>
                </form>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin: 0 10px;width: 85%;">
                Post Something
                </button>
                <?php
                    if($userLogeedIn != $username){
                        echo "<div class='profile_info_button'>";
                            echo  $logged_in_user_obj->getMutualFriends($username). " Mutual Friends";
                        echo "</div>";
                    }
                ?>
            </div>
            
            <div class="user-details column" style="width: 700px;float: right;"> 
                
                    <div class="profile_main_column">
                    <ul class="nav nav-tabs" role="tablist" id="profileTabs">
                        <li role="presentation"class="active" >
                            <a  href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeeds</a>
                        </li>
                        <li role="presentation">
                            <a  href="#message_div"  aria-controls="message_div" role="tab" data-toggle="tab">Messages</a>
                        </li>
                        </ul>
                        <div class="tab_content">
                            <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
                            <div class="posts_area"></div>
                                <img id="loading" src="assets/images/icons/loading.gif" >
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="message_div">
                            <?php
                                    echo "<h4>You and <a href='".$username."'>".$profile_user_obj->getFirstAndLastName()."</a></h4><hr><br>";
                                    echo "<div class='loaded_message' id='scroll_message'>";
                                    echo $message_obj->getMessage($username);
                                    echo "</div>";
                                
                            ?>
                            <div class="message_post">
                                <form action="" method="POST">
                                    <textarea name='message_body' id='message_textarea' placeholder='Write Your Message...'></textarea>
                                    <input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
                                </form>
                            </div>
                            
                        </div>
                            <script>
                                    var div = document.getElementById("scroll_message");
                                    div.scrollTop = div.scrollHeight;
                            </script>
                            </div>
                        </div>
                    </div>
                    
                    <!-- model -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Post Something!</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <p>This will apper on the user's profile page and also their newsfeed for your friends to see!</p>
                                <form action="" class="profile_post" method="POST">
                                    <div class="from-group">
                                        <textarea name="post_body" class="form-control" style="width:100%;"></textarea>
                                        <input type="hidden" name="user_form" value="<?php echo $userLogeedIn; ?>">
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
                var userLoggedIn = '<?php echo $userLogeedIn; ?>';
                var profileUsername = '<?php echo $username; ?>';
                $(document).ready(function(){
                    $('#loading').show();

                    //ajex request for loding first posts
                    $.ajax({
                        url:"includes/handler/ajax_load_profile_posts.php",
                        type:"POST",
                        data:"page=1&userLoggedIn="+userLoggedIn+"&profileUsername="+profileUsername,
                        cache:false,

                        success:function(data){
                            $('#loading').hide();
                            $('.posts_area').html(data);
                        }
                    });
                    $(window).scroll(function(){
                        //div containing height
                        var height = $('.posts_area').height();
                        var scroll_top = $(this).scrollTop();
                        var page =$('.posts_area').find('.nextPage').val();
                        var noMorePosts = $('.posts_area').find('.noMorePosts').val();

                        if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
                            $('#loading').show();

                           var ajaxReq= $.ajax({
                                    url:"includes/handler/ajax_load_profile_posts.php",
                                    type:"POST",
                                    data:"page=" + page + "&userLoggedIn=" + userLoggedIn+"&profileUsername="+profileUsername,
                                    cache:false,

                                    success:function(response){
                                        $('.posts_area').find('.nextPage').remove();  //remove current page
                                        $('.posts_area').find('.noMorePosts').remove(); 

                                        $('#loading').hide();
                                        $('.posts_area').append(response);
                                    }
                            });
                        }
                        return false;
                    });
                });
            </script>
            </div>
        </div>
    </body>
</html>
