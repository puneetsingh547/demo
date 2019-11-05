<?php
    ob_start();  //start output buffering

    session_start();
    $timezone = date_default_timezone_set("Asia/Kolkata");
    $con = mysqli_connect("localhost","root","puneet","socialMedia");

    if(mysqli_connect_errno()){
        echo "Database connection Error".mysqli_connect_error($con);
    }
?>