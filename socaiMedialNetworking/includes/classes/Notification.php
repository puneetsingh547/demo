<?php
class Notification {
    private $conn;
    private $user_obj;
    
    public function __construct($conn, $user){
        $this->conn = $conn;
        $this->user_obj = new User($conn, $user);
    }
    public function getUnreadMessages(){
        $userLoggedIn = $this->user_obj->getUserName();
        $query = mysqli_query($this->conn, "SELECT * from notifications where viewed='no' and user_to='$userLoggedIn'");
        return mysqli_num_rows($query);
    }
    public function getNotification($data, $limit){

            $page = $data['page'];
            $userLoggedIn = $this->user_obj->getUserName();
            $return_string = "";

            if($page == 1)
                $start = 0;
            else
                $start = ($page - 1) * $limit;

            $set_viewed_query = mysqli_query($this->conn, "UPDATE notifications set viewed='yes' where user_to='$userLoggedIn'");

            $query = mysqli_query($this->conn, "SELECT * from notifications where user_to='$userLoggedIn' order by id desc");

            if(mysqli_num_rows($query) == 0 ){
                echo "You have no Notification!";
                return;
            }
            $num_iteration = 0;     //number of message checked
            $count =1;              //number of message posted

            while($row = mysqli_fetch_array($query)){

                if($num_iteration++ < $start)
                    continue;

                if($count > $limit)
                    break;
                else
                    $count++;

                $user_from = $row['user_from'];
                $user_query_data =mysqli_query($this->conn, "SELECT * from users where username='$user_from'");
                $user_data =  mysqli_fetch_array($user_query_data);

                //timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($row['datetime']); //time of post
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

                $opened = $row['opened'];
                $style = ($row['opened'] == 'no') ? "background-color:#DDEDFF;" : "";

                $return_string .= "<a href='".$row['link']."'>
                                    <div class='resultDisplay resultdisplayNotification' style='".$style."'>
                                        <div class='notificationProfilePic'>
                                        <img src='".$user_data['profile_pic']."'>
                                        </div> 
                                        <p class='timestamp_smaller' id='gray'>".$days."</p>".$row['messages']."
                                    </div>
                                  </a>";
            }

            //if posts were loaded
            if($count > $limit)
                $return_string .= "<input type='hidden' class='nextPageDropdownData' value='" . ($page + 1) . "'><input type='hidden' class='noMorePostDropdownData' value='false'>";
            else
                $return_string .= "<input type='hidden' class='noMorePostDropdownData' value='true'><p style='text-align:center;'>No more notification to load!</p>";

            return $return_string;
    }
    public function insertNotification($post_id, $user_to, $type){
        $userLoggedIn = $this->user_obj->getUserName();
        $userLoggedInName = $this->user_obj->getFirstAndLastName();

        $date_time = date("Y-m-d H:i:s");

        switch($type){
            case 'comment':
                $message = $userLoggedInName . " commented on your post";
                break;
            case 'like':
                $message = $userLoggedInName . " liked your post";
                break;
            case 'profile_post':
                $message = $userLoggedInName . " posted on your post";
                break;
            case 'comment_non_owner':
                $message = $userLoggedInName . " commented on a post you comment on";
                break;
            case 'profile_comment':
                $message = $userLoggedInName . " commented on your profile post";
                break;
        }
        $link ="post.php?id=" . $post_id;

        $insert_query = mysqli_query($this->conn, "INSERT INTO notifications(user_to, user_from, messages, link, datetime, opened, viewed) values 
        ('$user_to','$userLoggedIn','$message', '$link','$date_time', 'no', 'no')");
    }
}
?>