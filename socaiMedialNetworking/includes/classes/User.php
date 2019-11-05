<?php
    class User {
        private $user;
        private $conn;

        public function __construct($conn, $user){
            $this->conn = $conn;
            $user_detail_query = mysqli_query($conn, "SELECT * from users where username='$user'");
            $this->user = mysqli_fetch_array($user_detail_query);
        } 

        public function getUserName(){
            return $this->user['username'];
        }
        public function getNumberOfFriendRequests(){
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT * from friend_request where user_to='$username'");
            return mysqli_num_rows($query);
        }
        public function getNumPosts(){
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT num_posts from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['num_posts'];
        }
        public function getFirstAndLastName(){
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT first_name, last_name from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['first_name']." ".$row['last_name'];
        }
        public function getProfilePic(){
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT profile_pic from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['profile_pic'];
        }
        public function getFriendArray(){
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT friend_array from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['friend_array'];
        }
        public function isClosed(){
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT user_closed from users where username='$username'");
            $row = mysqli_fetch_array($query);

            if($row['user_closed'] == 'yes'){
                return true;
            } else{
                return false;
            }
        }
        public function isFriend($username_to_check){
            $usernameComma = ",".$username_to_check.",";

            if((strstr($this->user['friend_array'],$usernameComma) || $username_to_check == $this->user['username'])){
                return true;
            }
            else {
                return false;
            }
        }
        public function didRecivedRequest($user_from){
            $user_to = $this->user['username'];
            $check_request_query = mysqli_query($this->conn, "SELECT * from friend_request where user_to='$user_to' and user_from='$user_from'");
            if(mysqli_num_rows($check_request_query) > 0){
                return true;
            }
            else
                return false;
        }
        public function didSendRequest($user_to){
            $user_from = $this->user['username'];
            $check_request_query = mysqli_query($this->conn, "SELECT * from friend_request where user_to='$user_to' and user_from='$user_from'");
            if(mysqli_num_rows($check_request_query) > 0){
                return true;
            }
            else
                return false;
        }
        public function removeFriend($user_to_remove){
            $logged_in_user = $this->user['username'];

            $query = mysqli_query($this->conn, "SELECT friend_array from users where username='$user_to_remove'");
            $row = mysqli_fetch_array($query);
            $friend_array_username = $row['friend_array'];

            $new_friend_array = str_replace($user_to_remove. ",","",$this->user['friend_array']);
            $remove_friend = mysqli_query($this->conn, "UPDATE users set friend_array='$new_friend_array' where username='$logged_in_user'");

            $new_friend_array = str_replace($this->user['username']. ",","",$friend_array_username);
            $remove_friend = mysqli_query($this->conn, "UPDATE users set friend_array='$new_friend_array' where username='$user_to_remove'");
        }
        public function sendRequest($user_to){
            $user_from = $this->user['username'];
            $query = mysqli_query($this->conn, "INSERT into friend_request(user_to, user_from) values('$user_to', '$user_from')");

        }
        public function getMutualFriends($user_to_check){
            $mutualFriends=0;
            $user_array = $this->user['friend_array'];
            $user_array_explode = explode("," , $user_array);

            $query = mysqli_query($this->conn, "SELECT friend_array from users where username='$user_to_check'");
            $row = mysqli_fetch_array($query);
            $user_to_check_array = $row['friend_array'];
            $user_to_check_array_explode = explode(",", $user_to_check_array);

            foreach($user_array_explode as $i){

                foreach($user_to_check_array_explode as $j){
                    if($i == $j && $i != ""){
                        $mutualFriends++;
                    }
                }
            }
            return $mutualFriends;
        }
    }
?>