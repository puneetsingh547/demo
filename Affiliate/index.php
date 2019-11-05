<?php
    include("includes/header.php");
    

    if(isset($_POST['post'])){
        $post = new Post($con , $userLoggedIn);
        $post->submitPost($_POST['post_text'] , "none");
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
        <div class="main_column column">
            <form action="index.php" class="post_form" method="POST">
                <textarea id="post_text" name="post_text" placeholder="Get something to say?"></textarea>
                <input type="submit" name="post" id="post_button" value="Post">
                <hr>
            </form>
            <div class="post_area"></div>
            <p id="loading">loading...</p>
        </div>
    <script>
        $(document).ready(function(){
            
        var userLoggedIn = "<?php echo $userLoggedIn; ?>";
        $("#loading").show();

        $.ajax({
            url:"includes/handlers/ajax_load_post.php",
            type:"POST",
            data:"page=1&userLoggedIn="+userLoggedIn,
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
                        url:"includes/handlers/ajax_load_post.php",
                        type:"POST",
                        data:"page=" + page + "&userLoggedIn="+userLoggedIn,
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
