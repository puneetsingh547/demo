<?php
    include("../../config/config.php");
    include("../classes/Post.php");
    include("../classes/User.php");

    $query = $_POST['query'];
    $userLoggedIn = $_POST['userLoggedIn'];

    $names = explode(" ", $query);

    $user_returned = mysqli_query($con , "SELECT * from users where username like '%$query%' limit 10");

    while($row = mysqli_fetch_array($user_returned)){
        echo $row['first_name']." " .$row['last_name'];
    }
?>