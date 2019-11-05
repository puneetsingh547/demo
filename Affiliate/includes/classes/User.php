<?php
    class User{
        private $user;
        private $con;

        public function __construct($con, $user){
            $this->con = $con;
            $user_details_query = mysqli_query($con , "SELECT * from users where username='$user'");
            $this->user = mysqli_fetch_array($user_details_query);
            
        }
        public function getUserName(){
            return $this->user['username'];
        }

        public function getNumPosts(){
            $username = $this->user['username'];
            $query = mysqli_query($this->con ,"SELECT num_posts from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['num_posts'];
        }

        public function getFirstAndLastName(){
            $username = $this->user['username'];
            $query = mysqli_query($this->con , "SELECT first_name , last_name from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['first_name'] . " " . $row['last_name'];
        }

        public function getProfilePic(){
            $username = $this->user['username'];
            $query = mysqli_query($this->con , "SELECT profile_pic from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['profile_pic'];
        }

        public function getFriendArray(){
            $username = $this->user['username'];
            $query = mysqli_query($this->con , "SELECT friend_array from users where username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['friend_array'];
        }

        public function isClosed(){
            $username = $this->user['username'];
            $query = mysqli_query($this->con , "SELECT * from users where username='$username'");
            $row = mysqli_fetch_array($query);
            if($row['user_closed'] == "yes")
                return true;
            else
                return false;
        }

        public function isFriend($check_username){
            $chack_friend_comma = "," . $check_username . ",";
            if(strstr($this->user['friend_array'], $check_username) || ($check_username == $this->user["username"])){
                return true;
            }
            else{
                return false;
            }
        }

        public function didReciveRequest($user_from){
            $user_to = $this->user['username'];
            $check_request_query = mysqli_query($this->con , "SELECT * from friend_request where user_to = '$user_to' and user_from='$user_from'");
            if(mysqli_num_rows($check_request_query) > 0){
                return true;
            }
            else {
                return false;
            }
        }

        public function didSendRequest($user_to){
            $user_from = $this->user['username'];
            $check_request_query = mysqli_query($this->con , "SELECT * from friend_request where user_to = '$user_to' and user_from='$user_from'");
            if(mysqli_num_rows($check_request_query) > 0){
                return true;
            }
            else {
                return false;
            }
        }  

        public function removeFreind($user_to_remove){
            $user_to_login = $this->user['username'];
            
            $query = mysqli_query($this->con , "SELECT friend_array from users where username='$user_to_remove'");
            $row = mysqli_fetch_array($query);
            $friend_array_username = $row['friend_array'];

            $remove_friend_array = str_replace($user_toremove.",", $this->user['friend_array']);
            $remove_friend = mysqli_query($this->con , "UPDATE users set friend_array = '$remove_friend_array' where username='$user_to_login'");
            
            $remove_friend_array = str_replace($user_to_login.",",$friend_array_username );
            $remove_friend = mysqli_query($this->con , "UPDATE users set friend_array = '$remove_friend_array' where username='$user_to_remove'");

            
        } 

        public function sendRequest($user_to){
            $user_from = $this->user['username'];
            $query = mysqli_query($this->con , "INSERT INTO friend_request (user_to , user_from) values ('$user_to' , '$user_from')");
        }

        public function getMetualFriends($user_to_check){
            $mutual_friends = 0;
            $user_array = $this->user['friend_array'];
            $user_array_explode = explode(",", $user_array);

            $query = mysqli_query($this->con , "SELECT friend_array from users where username = '$user_to_check'");
            $row = mysqli_fetch_array($query);
            $user_to_check_array = $row['friend_array'];
            $user_to_check_array_explode = explode("," , $user_to_check_array);

            foreach($user_array_explode as $i ){

                foreach($user_to_check_array_explode as $j ){
                    if($i == $j && $i != "")
                        $mutual_friends++;
                }
            }
            return $mutual_friends;
        }
    }
?>
