<?php
	session_start();
?>
<?php
	$ss=$_SESSION["sessIdOfficer"]=$_SESSION["oId"];
	$connection = new mysqli("localhost", "root", "puneet", "PrisonManagementSystem");
	if($connection->connect_error){
		die("connection failled ".$connection->connect_error );
	}
	 
	$selectQuery = "select * from OfficerDetail where OfficerId ='$ss'";
	
	$result = $connection->query($selectQuery);
	
	while ($row = $result->fetch_assoc()){
	echo "<br> id: ". $row["OfficerId"]. " <br> Name: ". $row["FirstName"]. " " . $row["LasttName"] . "<br>address : ".$row["Address"]. "<br>Age : ".$row["Age"]."<br>Phone : ".$row["Phone"]." ";
	 }
	mysqli_close($connection);
?>
<html>
	<head>
		<title>PROFILE | OFFICER</title>
	</head>
	<body>
		<div>
			<br><a href="IPC_section.php">IPC Section</a><br>
			<br><a href="visitor.php">Visitor</a><br>
			<br><a href="prisoner.php">Prisoner</a><br>
		</div>
	</body>
</html>
