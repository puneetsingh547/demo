<?php
include("../../config/config.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$name = explode(" ", $query);

if(strpos($query , "_") !== false)
    $userReturnQuery = mysqli_query($conn, "SELECT * from users where username like '%$query%' and user_closed='no' limit 8");
else if(count($name) == 2)
    $userReturnQuery = mysqli_query($conn, "SELECT * from users where (first_name like '$name[0]%' and last_name like '$name[1]%') and user_closed='no' limit 8");
else
    $userReturnQuery = mysqli_query($conn, "SELECT * from users where (first_name like '$name[0]%' or last_name like '$name[0]%') and user_closed='no' limit 8");

if($query != ""){
    while($row = mysqli_fetch_array($userReturnQuery)){
        $user = new User($conn, $userLoggedIn);

        if($row['username'] != $userLoggedIn)
            $mutualFriends = $user->getMutualFriends($row['username']) . " friends in common";
        else
            $mutualFriends = "";
        echo "<div class='resultDisplay'>
                <a href='".$row['username']."' style='color:#1485bd;'>
                    <div class='liveSearchProfilePic'>
                        <img src='".$row['profile_pic']."'>
                    </div>
                    <div class='liveSearchText'>
                        ".$row['first_name']." ".$row['last_name']."
                        <p>".$row['username']."</p>
                        <p id='grey'>".$mutualFriends."</p>
                    </div>
                </a>
                <hr>
        </div>";
    }
}
?>