<?php
	ob_start();
	session_start();
	
	$timezone = date_default_timezone_set('Asia/Kolkata');
	$conn = mysqli_connect("localhost", "root", "puneet", "stack"); 
	if(mysqli_connect_errno()){
		echo "MYSQL connection not stablish : ".$mysqli_connect_errno();
	}
?>
