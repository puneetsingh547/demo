<?php
	session_start();
	$id=$_SESSION["prisoniorId"];
	
	$fName=$_POST["fName"];
	$lName=$_POST["lName"];
	$ipcSection=$_POST["sectionNo"];
	$address=$_POST["address"];
	$dob=$_POST["DOB"];
	$doi=$_POST["DOI"];
	$doo=$_POST["iTime"];
	
	$connection = new mysqli("localhost", "root", "puneet", "PrisonManagementSystem");
	if($connection->connect_error){
		die($connection->connect_error);
	}
	$insertQuery="update PrisonerDetail set FirstName=?,LastName=?,IPC_section=?,Address=?,DOB=?,DateOfIn=?,ImprisonmentDate=? where prisonerNumber=".$id;
	
	$prepareStmt=$connection->prepare($insertQuery);
	$prepareStmt->bind_param("sssssss",$fName,$lName,$ipcSection,$address,$dob,$doi,$doo);	
	$prepareStmt->execute();
	mysqli_close($connection);
?>
<html>
	<head>
		<title>Prisoner | Details |Update</title>
	</head>
	<body>
		<div>
			<p><a href="index.php">Goto Login </a></p>
			<table>
				<tr>
					<th>Prisoner Number </th>
					<td><?php echo $prisonNo; ?></td>
				</tr>
				<tr>
					<th>Prisoner Name </th>
					<td><?php echo $fName." ".$lName;?></td>
				</tr>
				<tr>
					<th>Under IPC Section </th>
					<td><?php echo $ipcSection;?></td>
				</tr>
				<tr>
					<th>Date of Birth </th>
					<td><?php echo $dob;?></td>
				</tr>
				<tr>
					<th>Date of In Jail</th>
					<td><?php echo $doi;?></td>
				</tr>
				<tr>
					<th>Date of Imprisonment </th>
					<td><?php echo $doo;?></td>
				</tr>
			</table>
			<p>have you any mistake in prisoner detail? <a href="updatePrisonerForm.php">Press Here</a></p>
			<p>Add another Prisoner detail <a href="prisoner.php">Press Here</a></p>
		</div>
	</body>
</html>
