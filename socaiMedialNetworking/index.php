<?php
    include("includes/header.php");
    // include("includes/classes/User.php");
    // include("includes/classes/Post.php");

    if(isset($_POST['post'])){
        $post = new Post($conn, $userLogeedIn);
        $post->submitPost($_POST['post_text'],'none');
    }
?>
            <div class="user-details column"> 
                <a href="<?php echo $user['username']; ?>"><img src="<?php echo $user['profile_pic']; ?>" alt=""></a>

                <div class="user-detail-left-right">
                    
                    <a href="<?php echo $user['username']; ?>"><?php echo $user['first_name']." ".$user['last_name']."<br>" ; ?></a>
                    <?php echo "Posts: ".$user['num_posts']."<br>"; 
                    echo "Likes: ".$user['num_likes']; ?>
                </div>
            </div>
            <div class="main-column column">
                <form action="index.php" method="POST" class="post-form" >
                    <textarea name="post_text" id="post-text" placeholder="Got something to say?"></textarea>
                    <input type="submit" name="post" id="post-button" value="Post">
                    <hr>
                   
                </form>
                <div class="posts_area"></div>
                <img id="loading" src="assets/images/icons/loading.gif" >
                <!-- <div id="loading">loading...</div> -->
            </div>
            <script>
                var userLoggedIn = '<?php echo $userLogeedIn; ?>';
                $(document).ready(function(){
                    $('#loading').show();

                    //ajex request for loding first posts
                    $.ajax({
                        url:"includes/handler/ajax_load_posts.php",
                        type:"POST",
                        data:"page=1&userLoggedIn="+userLoggedIn,
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
                                    url:"includes/handler/ajax_load_posts.php",
                                    type:"POST",
                                    data:"page=" + page + "&userLoggedIn=" + userLoggedIn,
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
    </body>
</html>
