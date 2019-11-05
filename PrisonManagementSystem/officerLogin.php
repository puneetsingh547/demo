<?php
	session_start();

	$oId = $_POST["OfficerId"];
	$oPass = $_POST["OfficerPassword"];
	
	$connection = new mysqli("localhost", "root", "puneet", "PrisonManagementSystem");
	if($connection->connect_error){
		die($connection->connect_error);
	}
	$selectLoginOfficer = "select * from OfficerDetail where OfficerId = ? and Password = ? ";
	
	$prepareStmt = $connection->prepare($selectLoginOfficer);
	$prepareStmt->bind_param("ss", $oId , $oPass);
	$prepareStmt->execute();
	
	$_SESSION["oId"] = $oId;
	mysqli_close($connection);
	
	header("location:officerProfile.php");
?>
