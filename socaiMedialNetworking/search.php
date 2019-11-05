<?php
include("includes/header.php");

if(isset($_GET['q'])){
    $query = $_GET['q'];
}
else {
    $query = "";
}

if(isset($_GET['type'])){
    $type = $_GET['type'];
}
else {
    $type = "name";
}
?>
<div class="main-column column" id="main_column">

<?php
 if($query == "")
    echo "You must enter in the search box.";

else {
    
    if($type == "username")
    $userReturnQuery = mysqli_query($conn, "SELECT * from users where username like '%query%' and user_closed='no' limit 8");

    else {
        $name = explode(" ", $query);

        if(count($name) == 3)
            $userReturnQuery = mysqli_query($conn, "SELECT * from users where (first_name like '$name[0]%' and last_name like '$name[2]%') and user_closed='no'");
        else if(count($name) == 2)
            $userReturnQuery = mysqli_query($conn, "SELECT * from users where (first_name like '$name[0]%' and last_name like '$name[1]%') and user_closed='no'");
        else
            $userReturnQuery = mysqli_query($conn, "SELECT * from users where (first_name like '$name[0]%' or last_name like '$name[0]%') and user_closed='no'");
    }
    if(mysqli_num_rows($userReturnQuery) == 0)
        echo "We cant find anyone with a ".$type." like ".$query;
    else 
        echo mysqli_num_rows($userReturnQuery)." Results found.<br><br>";
    
    echo "<p id='gray'>Try searching for:</p>";
    echo "<a href='search.php?q=".$query."&type=name'>Names</a>, <a href='search.php?q=".$type."&type=username'>Username</a><br><br><hr>";

    while($row = mysqli_fetch_array($userReturnQuery)){
        $user_obj = new User($conn, $row['username']);

        $button = "";
        $mutual_friends = "";

        if($user['username'] != $row['username']){

            //generate button for friendship status

            if($user_obj->isFriend($row['username']))
                $button = "<input type='submit' name='".$row['username']."' class='danger' value='Remove Friends'>";
            else if($user_obj->didRecivedRequest($row['username']))
                $button = "<input type='submit' name='".$row['username']."' class='warning' value='Respond to request'>";
            else if($user_obj->didSendRequest($row['username']))
                $button = "<input class='default' value='Request Sent'>";
            else 
                $button = "<input type='submit' name='".$row['username']."' class='success' value='Add Friends'>";

            $mutual_friends = $user_obj->getMutualFriends($row['username'])." Friends in common";
        }
        echo "<div class='search_result'>
            <div class='searchFriendsPageButtons'>
                <form action='' method=''POST> ".$button."<br> </form>
            </div>
            <div class='restlts_profile_pic'>
                <a href=' " . $row['username'] . "'><img src='".$row['profile_pic']."' style='height:100px;width:100px;'></a>
            </div>
            <a href='".$row['username']."'>".$row['first_name']." ".$row['last_name']."</a>
            <p id='gray'>".$row['username']."</p><br>
            ". $mutual_friends . "
        </div><hr>";
    }
}
?>
</div>