<?php
    class Message{
        private $conn;
        private $user_obj;
        
        public function __construct($conn, $user){
            $this->conn = $conn;
            $this->user_obj = new User($conn, $user);
        }
        public function getMostRecentUser(){
            $userLoggedIn = $this->user_obj->getUserName();

            $query = mysqli_query($this->conn, "SELECT user_to, user_from from messages where user_to='$userLoggedIn' or user_from='$userLoggedIn' order by id desc limit 1");

            if(mysqli_num_rows($query) == 0)
                return false;

            $row = mysqli_fetch_array($query);
            $user_to = $row['user_to'];
            $user_from = $row['user_from'];

            if($user_to != $userLoggedIn)       // user_to = message recover
                return $user_to;
            else
                return $user_from;              // message sender or user
        }
        public function sendMessage($user_to, $body,$date){
            if($body != ""){
                $userLoggedIn = $this->user_obj->getUserName();
                if($user_to == $userLoggedIn){
                    return header("location:messages.php");    }
                $query = mysqli_query($this->conn, "INSERT messages (user_to, user_from, body,date,opened,viewed, deleted) values ('$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
            }
            
        }
        public function getMessage($otherUser){
            $userLoggedIn = $this->user_obj->getUserName();
            $data = "";

            $query = mysqli_query($this->conn, "UPDATE messages set opened='yes' where user_to='$userLoggedIn' and user_from='$otherUser'");        // incase message have been seen
            // show messages only sender and reciver
            $gat_message_query = mysqli_query($this->conn, "SELECT * FROM messages where (user_to='$userLoggedIn' and user_from='$otherUser') or (user_from='$userLoggedIn' and user_to='$otherUser')");

            while($row = mysqli_fetch_array($gat_message_query)){

                $user_to = $row['user_to'];
                $user_from = $row['user_from'];
                $body = $row['body'];

                $div_top = ($user_to == $userLoggedIn) ? "<div class='message' id='green'>" : "<div class='message' id='blue'>";

                $data = $data . $div_top . $body ."</div><br><br>";
            }
            return $data;
        }
        public function getLatestMessage($userLoggedIn, $user2){
            $details_array = array();

            $query = mysqli_query($this->conn, "SELECT body, user_to,date from messages where (user_to='$userLoggedIn' and user_from='$user2') or (user_to='$user2' and user_from='$userLoggedIn') order by id desc limit 1");

            $row = mysqli_fetch_array($query);
            $sent_by = ($row['user_to'] == $userLoggedIn) ? "They said: " : "You said: ";

            //timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($row['date']); //time of post
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

            array_push($details_array, $sent_by);
            array_push($details_array, $row['body']);
            array_push($details_array, $days);

            return $details_array;
        }
        public function getConvos(){
            $userLoggedIn = $this->user_obj->getUserName();
            $return_string = "";
            $convos = array();

            $query = mysqli_query($this->conn, "SELECT user_to, user_from, date from messages where user_to='$userLoggedIn' or user_from='$userLoggedIn' order by id desc");

            while($row = mysqli_fetch_array($query)) {
                $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

                if(! in_array($user_to_push , $convos)){        //for add unique username 
                    array_push($convos, $user_to_push);
                }
            }
            foreach($convos as $username){
                $user_found_obj = new User($this->conn, $username);
                $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);        // get the array value from the function getLatestMessage

                $dots = (strlen($latest_message_details[1]) >= 12) ? "..." : "";
                $split = str_split($latest_message_details[1], 12);     //convert string into array return 12 cherector of array
                $split = $split['0'] . $dots;

                $return_string .= "<a href='messages.php?u=$username'> <div class='user_found_messages'>
                                  <img src='".$user_found_obj->getProfilePic()."' style='border-radius: 5px; margin-right: 5px;'>
                                  ".$user_found_obj->getFirstAndLastName()."
                                  <span class='timestamp_smaller' id='gray'>".$latest_message_details[2]."</span>
                                  <p id='gray' style='margin:0;'>".$latest_message_details[0].$split."</p>
                                  </div>
                                  </a>";
            }
            return $return_string;
        }
        public function getConvosDropdown($data, $limit){
            $page = $data['page'];
            $userLoggedIn = $this->user_obj->getUserName();
            $return_string = "";
            $convos = array();

            if($page == 1)
                $start = 0;
            else
                $start = ($page - 1) * $limit;

            $set_viewed_query = mysqli_query($this->conn, "UPDATE messages set viewed='yes' where user_to='$userLoggedIn'");

            $query = mysqli_query($this->conn, "SELECT user_to, user_from, date from messages where user_to='$userLoggedIn' or user_from='$userLoggedIn' order by id desc");

            while($row = mysqli_fetch_array($query)) {
                $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

                if(! in_array($user_to_push , $convos)){
                    array_push($convos, $user_to_push);
                }
            }

            $num_iteration = 0;     //number of message checked
            $count =1;              //number of message posted

            foreach($convos as $username){

                if($num_iteration++ < $start)
                    continue;

                if($count > $limit)
                    break;
                else
                    $count++;

                $is_unread_query = mysqli_query($this->conn, "SELECT opened from messages where user_to='$userLoggedIn' and user_from='$username' order by id desc");
                $row = mysqli_fetch_array($is_unread_query);
                $style = ($row['opened'] == 'no') ? "background-color:#DDEDFF;" : "";

                $user_found_obj = new User($this->conn, $username);
                $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);        // get the array value from the function getLatestMessage

                $dots = (strlen($latest_message_details[1]) >= 12) ? "..." : "";
                $split = str_split($latest_message_details[1], 12);     //convert string into array return 12 cherector of array
                $split = $split['0'] . $dots;

                $return_string .= "<a href='messages.php?u=$username'> 
                                 <div class='user_found_messages' style='".$style."'>
                                  <img src='".$user_found_obj->getProfilePic()."' style='border-radius: 5px; margin-right: 5px;'>
                                  ".$user_found_obj->getFirstAndLastName()."
                                  <span class='timestamp_smaller' id='gray'>".$latest_message_details[2]."</span>
                                  <p id='gray' style='margin:0;'>".$latest_message_details[0].$split."</p>
                                  </div>
                                  </a>";
            }

            //if posts were loaded
            if($count > $limit)
                $return_string .= "<input type='hidden' class='nextPageDropdownData' value='" . ($page + 1) . "'><input type='hidden' class='noMorePostDropdownData' value='false'>";
            else
                $return_string .= "<input type='hidden' class='noMorePostDropdownData' value='true'><p style='text-align:center;'>No more messages to load!</p>";

            return $return_string;
        }

        public function getUnreadMessages(){
            $userLoggedIn = $this->user_obj->getUserName();
            $query = mysqli_query($this->conn, "SELECT * from messages where viewed='no' and user_to='$userLoggedIn'");
            return mysqli_num_rows($query);
        }
    }
?> 