<?php

class Messages{

    private $con;
    private $user_obj;

    public function __construct($con , $user){
        $this->con = $con;
        $this->user_obj = new User($con , $user);
    }
    public function getMostRecentPost(){
        $userLoggedIn = $this->user_obj->getUsername();

        $query = mysqli_query($this->con , "SELECT user_to , user_from from messages where user_to = '$userLoggedIn' or user_from='$userLoggedIn' order by id desc limit 1");

        if(mysqli_num_rows($query) == 0){
            return false;
        }
        $row = mysqli_fetch_array($query);
        $user_to = $row['user_to'];
        $user_from = $row['user_from'];

        if($user_to != $userLoggedIn)
            return $user_to;
        else
            return $user_from;
    }
    public function sendMessage($user_to, $body,$date){
        if($body != ""){
            $userLoggedIn = $this->user_obj->getUsername();
            // if($user_to == $userLoggedIn){
            //     return header("location:messages.php");    }
            $query = mysqli_query($this->con, "INSERT messages (user_to, user_from, body,date,opened,viewed, deleted) values ('$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
        }
        
    }
    public function getMessage($otherUser){
        $userLoggedIn = $this->user_obj->getUsername();
        $data = "";

        $query = mysqli_query($this->conn, "UPDATE messages set opened='yes' where user_to='$userLoggedIn' and user_from='$otherUser'");        // incase message have been seen
        // show messages only sender and reciver
        $gat_message_query = mysqli_query($this->con, "SELECT * FROM messages where (user_to='$userLoggedIn' and user_from='$otherUser') or (user_from='$userLoggedIn' and user_to='$otherUser')");

        while($row = mysqli_fetch_array($gat_message_query)){

            $user_to = $row['user_to'];
            $user_from = $row['user_from'];
            $body = $row['body'];

            $div_top = ($user_to == $userLoggedIn) ? "<div class='message' id='green'>" : "<div class='message' id='blue'>";

            $data = $data . $div_top . $body ."</div><br><br>";
        }
        return $data;
    }
    
}
?>