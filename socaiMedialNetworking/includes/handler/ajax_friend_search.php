<?php
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];


$name = explode(" ", $query);       //search alternate after space

if(strpos($query, "_") !== false){      //strpos= find _ from the string
    $userReturned = mysqli_query($conn, "SELECT * from users where username LIKE '$query%' and user_closed='no' limit 8");
    
}
else if(count($name) == 2){
    $userReturned = mysqli_query($conn, "SELECT * from users where (first_name like '%$name[0]%' and last_name like '%$name[1]%') and user_closed='no' limit 8");
}
else{
    $userReturned = mysqli_query($conn, "SELECT * from users where (first_name like '%$name[0]%' or last_name like '%$name[0]%') and user_closed='no' limit 8");
}

if($query != ""){
    while($row = mysqli_fetch_array($userReturned)){
        $user = new User($conn, $userLoggedIn);

        if($row['username'] != $userLoggedIn){
            $mutual_friends = $user->getMutualFriends($row['username']). " friends in column";
            
        }
        else{
            $mutual_friends = "";
        }
        
        if(!$user->isFriend($row['username'])){

            echo "<div class='resultsDisplay'>
                    <a href='messages.php?u=".$row['username']."' style='color:#000;'>
                        <div class='liveSearchProfilePic'>
                            <img src='".$row['profile_pic']."'>
                        </div>
                        <div class='liveSearchText'>
                            ".$row['first_name']." ". $row['last_name']."
                            <p style='margin:0px;'>".$row['username']."</p>
                            <p id='gray'>".$mutual_friends."</p>
                        </div>
                    </a>
                 </div>";
        }
    }
}
?>