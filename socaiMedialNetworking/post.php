<?php
    include("includes/header.php");

    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    else{
        $id = 0;
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
            <div class="posts-area">
                <?php
                    $post = new Post($conn, $userLogeedIn);
                    $post->getSinglePost($id);
                ?>
            </div>
        </div>