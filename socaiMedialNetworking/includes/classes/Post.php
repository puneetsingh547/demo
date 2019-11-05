<?php
    class Post {
        private $user_obj;
        private $conn;

        public function __construct($conn, $user){
            $this->conn = $conn;
            $this->user_obj = new User($conn, $user);
        }
        public function submitPost($body, $user_to){
            $body = strip_tags($body);  //remove html tages
            $body = mysqli_real_escape_string($this->conn, $body);  //insert sql comma semi-colon etc
            $check_empty = preg_replace('/\s+/','',$body);
            
            if($check_empty != "")
            { 
                //corrent date and time
                $date_added = date("Y-m-d H:i:s");
                $added_by = $this->user_obj->getUserName();

                if($user_to == $added_by ){
                    $user_to = "none";
                }
                
                //insert post
                $query = mysqli_query($this->conn, "INSERT INTO posts(body,added_by,user_to,date_added,user_closed,deleted,likes) values('$body', '$added_by','$user_to','$date_added','no','no','0')");
                $returned_id = mysqli_insert_id($this->conn);

                // insert notification
                if($user_to != 'none'){
                    $notification = new Notification($this->conn, $added_by);
                    $notification->insertNotification($returned_id, $user_to, "profile_post");
                }

                //update post count for user
                $num_posts = $this->user_obj->getNumPosts();
                $num_posts++;
                $update_query = mysqli_query($this->conn, "UPDATE users set num_posts='$num_posts' WHERE username='$added_by'");
            }
        }
        public function loadPostsFriends($data, $limit){
            $page = $data['page'];
            
            $userLoggedIn = $this->user_obj->GetUserName();

            if($page == 1){
                $start = 0; }
            else {
                $start = ($page - 1) * $limit; 
             }

            $str = "";  //string to return
            $data_query = mysqli_query($this->conn, "SELECT * from posts where deleted='no' order by id desc");

            if(mysqli_num_rows($data_query) > 0 ){

                $num_iteration = 0;     //number of result checked 
                $count = 1;

                while($row = mysqli_fetch_array($data_query)){
                    $id = $row['id'];
                    $body = $row['body'];
                    $added_by = $row['added_by'];
                    $date_time = $row['date_added'];

                    if($row['user_to'] == "none"){
                        $user_to = "";
                    }
                    else{
                        $user_to_obj = new User($this->conn, $row['user_to']);
                        $user_to_name = $user_to_obj->getFirstAndLastName();
                        $user_to = " to <a href='".$row['user_to']."'>".$user_to_name."</a>";
                    }
                    // check if user who posted ,has closed their account
                    $added_by_obj = new User($this->conn, $added_by);
                    if($added_by_obj->isClosed()){
                        continue;
                    }
                    $user_logged_obj = new User($this->conn, $userLoggedIn);
                    if($user_logged_obj->isFriend($added_by)){

                    
                    if($num_iteration++ < $start)
                        continue;
                    // once 10 posts has been loaded
                    if($count > $limit){
                        break;
                    }
                    else{
                        $count++;
                    }

                    if($userLoggedIn == $added_by)
                        $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                    else{
                        $delete_button = "";
                    }

                    $user_details_query = mysqli_query($this->conn, "SELECT first_name, last_name, profile_pic from users where username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    ?>

                    <script>
                         function toggle<?php echo $id; ?>() {

                            var element = document.getElementById("toggleComment<?php echo $id; ?>");

                            if(element.style.display == "block")
                                element.style.display = "none";
                            else
                                element.style.display = "block";
                        }
                    </script>
                    <?php
                    $noOfComments ="";
                    $comments_check = mysqli_query($this->conn, "SELECT * from comments where post_id='$id'");
                    $comments_check_num = mysqli_num_rows($comments_check);
                    //timeframe
                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time); //time of post
                    $end_date = new DateTime($date_time_now);  //current time
                    $interval = $start_date->diff($end_date);
                                
                    if($interval->y >= 1){
                        if($interval == 1){
                            $time_massage = $interval->y ." year ago"; //one year ago
                        }
                        else{
                            $time_massage = $interval->y. " years ago";   //more then one year ago 
                        }
                    }
                    else if($interval->m >= 1) {
                        if($interval->d == 0){
                            $days = " ago";
                        }
                        else if($interval->d >=1 ){
                            $days = $interval->d ." day ago";
                        }
                        else {
                            $days = $interval->d ." days ago";
                        }

                        if($interval->m == 1){
                            $time_massage = $interval->m.  "month".$days;
                        }
                        else {
                            $time_massage = $interval->m. " months".$days;
                        }
                    }
                    else if($interval->d >= 1){
                            if($interval->d ==1 ){
                                $days = " yesterday";
                            }
                            else {
                                $days = $interval->d ." days ago";
                            }
                    }
                    else if($interval->h >=1 ){
                        if($interval->h ==1 ){
                            $days = $interval->h ." hour ago";
                        }
                        else {
                            $days = $interval->h ." hours ago";
                        }
                    }
                    else if($interval->i >=1 ){
                        if($interval->i ==1 ){
                            $days = $interval->i ." minute ago";
                        }
                        else {
                            $days = $interval->i ." minutes ago";
                        }
                    }
                    else{
                        if($interval->s <30){
                            $days = "just now";
                        }
                        else {
                            $days = $interval->s ." seconds ago";
                        }
                    } 
                    $str .= "<div class='status_post' onClick='javascript:toggle$id()'>
                                <div class='post_profile_pic'>
                                    <img src='$profile_pic' style='width:50px;'>
                                </div>
                                <div class='posted_by' style='color:#ACACAC;'>
                                    <a href='$added_by'> $first_name  $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;  $days
                                    $delete_button
                                </div>
                                <div id='post_body'>
                                    $body</br>
                                </div>
                                <div class='newfeedPostOption' style='color: #20AAE5;'>
                                Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
                                <iframe src='like.php?post_id=$id' style='border:0; width:170px;height:17px;'></iframe>
                                </div>

                            </div>
                            <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' style='width:100%; max-height:250px; border:none;'></iframe>
                            </div>
                            <hr>";
                }
                ?>
                <script>
                
                    $(document).ready(function(){
                        $('#post<?php echo $id; ?>').on('click', function(){
                            bootbox.confirm("Are you sure you want to delete this post?", function(result){
                                $.post("includes/form_handler/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

                                if(result)
                                    location.reload();
                            });
                        });
                    });
                </script>
                <?php
                }//End while loop
                if($count > $limit)
                    $str .= "<input type='hidden' class='nextPage' value='". ($page +1) ."'>
                                <input type='hidden' class='noMorePosts' value='false'>";
                else
                    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align:centre'>No more posts to show!</p>";
            }
            echo $str;
        }
        public function loadProfilePosts($data, $limit){
            $page = $data['page'];
            $profileUser = $data['profileUsername'];
            $userLoggedIn = $this->user_obj->GetUserName();

            if($page == 1){
                $start = 0; }
            else {
                $start = ($page - 1) * $limit;
               // echo $start;  
             }

            $str = "";  //string to return
            $data_query = mysqli_query($this->conn, "SELECT * from posts where deleted='no' and ((added_by='$profileUser' and user_to='none') or user_to='$profileUser') order by id desc");

            if(mysqli_num_rows($data_query) > 0 ){

                $num_iteration = 0;     //number of result checked 
                $count = 1;

                while($row = mysqli_fetch_array($data_query)){
                    $id = $row['id'];
                    $body = $row['body'];
                    $added_by = $row['added_by'];
                    $date_time = $row['date_added'];

                    
                    
                    if($num_iteration++ < $start)
                        continue;
                    // once 10 posts has been loaded
                    if($count > $limit){
                        break;
                    }
                    else{
                        $count++;
                    }

                    if($userLoggedIn == $added_by)
                        $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                    else{
                        $delete_button = "";
                    }

                    $user_details_query = mysqli_query($this->conn, "SELECT first_name, last_name, profile_pic from users where username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    ?>

                    <script>
                         function toggle<?php echo $id; ?>() {

                            var element = document.getElementById("toggleComment<?php echo $id; ?>");

                            if(element.style.display == "block")
                                element.style.display = "none";
                            else
                                element.style.display = "block";
                        }
                    </script>
                    <?php
                    $noOfComments ="";
                    $comments_check = mysqli_query($this->conn, "SELECT * from comments where post_id='$id'");
                    $comments_check_num = mysqli_num_rows($comments_check);
                    //timeframe
                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time); //time of post
                    $end_date = new DateTime($date_time_now);  //current time
                    $interval = $start_date->diff($end_date);
                                
                    if($interval->y >= 1){
                        if($interval == 1){
                            $time_massage = $interval->y ." year ago"; //one year ago
                        }
                        else{
                            $time_massage = $interval->y. " years ago";   //more then one year ago 
                        }
                    }
                    else if($interval->m >= 1) {
                        if($interval->d == 0){
                            $days = " ago";
                        }
                        else if($interval->d >=1 ){
                            $days = $interval->d ." day ago";
                        }
                        else {
                            $days = $interval->d ." days ago";
                        }

                        if($interval->m == 1){
                            $time_massage = $interval->m.  "month".$days;
                        }
                        else {
                            $time_massage = $interval->m. " months".$days;
                        }
                    }
                    else if($interval->d >= 1){
                            if($interval->d ==1 ){
                                $days = " yesterday";
                            }
                            else {
                                $days = $interval->d ." days ago";
                            }
                    }
                    else if($interval->h >=1 ){
                        if($interval->h ==1 ){
                            $days = $interval->h ." hour ago";
                        }
                        else {
                            $days = $interval->h ." hours ago";
                        }
                    }
                    else if($interval->i >=1 ){
                        if($interval->i ==1 ){
                            $days = $interval->i ." minute ago";
                        }
                        else {
                            $days = $interval->i ." minutes ago";
                        }
                    }
                    else{
                        if($interval->s <30){
                            $days = "just now";
                        }
                        else {
                            $days = $interval->s ." seconds ago";
                        }
                    } 
                    $str .= "<div class='status_post' onClick='javascript:toggle$id()'>
                                <div class='post_profile_pic'>
                                    <img src='$profile_pic' style='width:50px;'>
                                </div>
                                <div class='posted_by' style='color:#ACACAC;'>
                                    <a href='$added_by'> $first_name  $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp;  $days
                                    $delete_button
                                </div>
                                <div id='post_body'>
                                    $body</br>
                                </div>
                                <div class='newfeedPostOption' style='color: #20AAE5;'>
                                Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
                                <iframe src='like.php?post_id=$id' style='border:0; width:170px;height:17px;'></iframe>
                                </div>

                            </div>
                            <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' style='width:100%; max-height:250px; border:none;'></iframe>
                            </div>
                            <hr>";
                ?>
                <script>
                
                    $(document).ready(function(){
                        $('#post<?php echo $id; ?>').on('click', function(){
                            bootbox.confirm("Are you sure you want to delete this post?", function(result){
                                $.post("includes/form_handler/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

                                if(result)
                                    location.reload();
                            });
                        });
                    });
                </script>
                <?php
                }//End while loop
                if($count > $limit)
                    $str .= "<input type='hidden' class='nextPage' value='". ($page +1) ."'>
                                <input type='hidden' class='noMorePosts' value='false'>";
                else
                    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align:centre'>No more posts to show!</p>";
            }
            echo $str;
        }
        public function getSinglePost($post_id){
            
            $userLoggedIn = $this->user_obj->GetUserName();
            $opened_query = mysqli_query($this->conn, "UPDATE notifications set opened='yes' where user_to='$userLoggedIn' and link like '%=$post_id'");

            $str = "";  //string to return
            $data_query = mysqli_query($this->conn, "SELECT * from posts where deleted='no' and id='$post_id'");

            if(mysqli_num_rows($data_query) > 0 ){

                    $row = mysqli_fetch_array($data_query);
                    $id = $row['id'];
                    $body = $row['body'];
                    $added_by = $row['added_by'];
                    $date_time = $row['date_added'];

                    if($row['user_to'] == "none"){
                        $user_to = "";
                    }
                    else{
                        $user_to_obj = new User($this->conn, $row['user_to']);
                        $user_to_name = $user_to_obj->getFirstAndLastName();
                        $user_to = " to <a href='".$row['user_to']."'>".$user_to_name."</a>";
                    }
                    // check if user who posted ,has closed their account
                    $added_by_obj = new User($this->conn, $added_by);
                    if($added_by_obj->isClosed()){
                        return;
                    }
                    $user_logged_obj = new User($this->conn, $userLoggedIn);
                    if($user_logged_obj->isFriend($added_by)){

                    if($userLoggedIn == $added_by)
                        $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                    else{
                        $delete_button = "";
                    }

                    $user_details_query = mysqli_query($this->conn, "SELECT first_name, last_name, profile_pic from users where username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    ?>

                    <script>
                         function toggle<?php echo $id; ?>() {

                            var element = document.getElementById("toggleComment<?php echo $id; ?>");

                            if(element.style.display == "block")
                                element.style.display = "none";
                            else
                                element.style.display = "block";
                        }
                    </script>
                    <?php
                    $noOfComments ="";
                    $comments_check = mysqli_query($this->conn, "SELECT * from comments where post_id='$id'");
                    $comments_check_num = mysqli_num_rows($comments_check);
                    //timeframe
                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time); //time of post
                    $end_date = new DateTime($date_time_now);  //current time
                    $interval = $start_date->diff($end_date);
                                
                    if($interval->y >= 1){
                        if($interval == 1){
                            $time_massage = $interval->y ." year ago"; //one year ago
                        }
                        else{
                            $time_massage = $interval->y. " years ago";   //more then one year ago 
                        }
                    }
                    else if($interval->m >= 1) {
                        if($interval->d == 0){
                            $days = " ago";
                        }
                        else if($interval->d >=1 ){
                            $days = $interval->d ." day ago";
                        }
                        else {
                            $days = $interval->d ." days ago";
                        }

                        if($interval->m == 1){
                            $time_massage = $interval->m.  "month".$days;
                        }
                        else {
                            $time_massage = $interval->m. " months".$days;
                        }
                    }
                    else if($interval->d >= 1){
                            if($interval->d ==1 ){
                                $days = " yesterday";
                            }
                            else {
                                $days = $interval->d ." days ago";
                            }
                    }
                    else if($interval->h >=1 ){
                        if($interval->h ==1 ){
                            $days = $interval->h ." hour ago";
                        }
                        else {
                            $days = $interval->h ." hours ago";
                        }
                    }
                    else if($interval->i >=1 ){
                        if($interval->i ==1 ){
                            $days = $interval->i ." minute ago";
                        }
                        else {
                            $days = $interval->i ." minutes ago";
                        }
                    }
                    else{
                        if($interval->s <30){
                            $days = "just now";
                        }
                        else {
                            $days = $interval->s ." seconds ago";
                        }
                    } 
                    $str .= "<div class='status_post' onClick='javascript:toggle$id()'>
                                <div class='post_profile_pic'>
                                    <img src='$profile_pic' style='width:50px;'>
                                </div>
                                <div class='posted_by' style='color:#ACACAC;'>
                                    <a href='$added_by'> $first_name  $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;  $days
                                    $delete_button
                                </div>
                                <div id='post_body'>
                                    $body</br>
                                </div>
                                <div class='newfeedPostOption' style='color: #20AAE5;'>
                                Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
                                <iframe src='like.php?post_id=$id' style='border:0; width:170px;height:17px;'></iframe>
                                </div>

                            </div>
                            <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' style='width:100%; max-height:250px; border:none;'></iframe>
                            </div>
                            <hr>";
                
                ?>
                <script>
                
                    $(document).ready(function(){
                        $('#post<?php echo $id; ?>').on('click', function(){
                            bootbox.confirm("Are you sure you want to delete this post?", function(result){
                                $.post("includes/form_handler/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

                                if(result)
                                    location.reload();
                            });
                        });
                    });
                </script>
                <?php
                }
                else{
                    echo "You are not frined ";
                    return;
                }
            }
            else {
                echo "no post found ";
                return;
            }
            echo $str;
        }    
    }
?>