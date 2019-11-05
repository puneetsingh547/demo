<?php
	session_start();
?>
<?php
			$fname=$lname=$address=$phone=$password="";
			$id = $_POST["OfficerId"];
			$fname= $_POST["FirstName"];
			$lname= $_POST["LastName"];
			$address= $_POST["Address"];
			$age = $_POST["Age"];
			$phone= $_POST["Phone"];
			$password= $_POST["Password"];
			
			$incriptPassword=password_hash($password,PASSWORD_DEFAULT);
			
			$servername = "localhost";
			$username = "root";
			$dbpass= "puneet";
			$dbname= "PrisonManagementSystem";
			
			$connection = new mysqli($servername,$username,$dbpass,$dbname);
			
			if($connection->connect_error){
				die("connection failed : ".$connection->connect_error);
			}
			$dbQuery = "INSERT INTO OfficerDetail (OfficerID,FirstName, LasttName, Address,Age ,Phone, Password)
			VALUES ('$id', '$fname', '$lname','$address',$age, $phone,'$incriptPassword' )";
			
			if($connection->query($dbQuery) === TRUE){
				header('location:officerProfile.php');
			} else{
				echo "failled!".$sql."<br>".$connection->error;
			}
			$_SESSION["sessIdOfficer"]=$id;
			$mysqli_close($connection);
?>
