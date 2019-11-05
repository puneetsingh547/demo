<?php 
	$fname = "";
	$lname = "";
	$em = "";
	$em2 = "";
	$password = "";
	$password2 = "";
	$date = "";
	$error_array = array();
	
	//registration form values 
	if(isset($_POST["reg_button"])){
		// first name
		$fname = strip_tags($_POST["reg_fname"]);  // Remove html tages(strip=string input outpot tags)
		$fname = str_replace(" ", "",$fname);  	   //remove space
		$fname = ucfirst(strtolower($fname));      //uppercase first letter
		$_SESSION['reg_fname'] = $fname;			//store values in session
		//last name
		$lname = strip_tags($_POST["reg_lname"]);
		$lname = str_replace(" ", "", $lname);
		$lname = ucfirst(strtolower($lname));
		$_SESSION['reg_lname'] = $lname;
		//email
		$em = strip_tags($_POST["reg_email"]);
		$em = str_replace(" ","",$em);
		$em = ucfirst(strtolower($em));
		$_SESSION['reg_email'] = $em;
		
		//email confirm
		$em2 = strip_tags($_POST["reg_email2"]);
		$em2 = str_replace(" ","",$em2);
		$em2 = ucfirst(strtolower($em2));
		$_SESSION['reg_email2'] = $em2;
		
		//password
		$password = strip_tags($_POST["reg_password"]);
		$password2 = strip_tags($_POST["reg_password2"]);
		
		$date = date("Y-m-d");
		
		if($em == $em2){
			if(filter_var($em , FILTER_VALIDATE_EMAIL)){
				$em = filter_var($em , FILTER_VALIDATE_EMAIL);
				//check if email is already exist
				$e_check = mysqli_query($conn, "SELECT email FROM users WHERE email='$em'");

				//count no of rows
				$num_rows = mysqli_num_rows($e_check);

				if($num_rows>0){
					array_push($error_array, "Email is already exist <br>");
				}
			}
			else{
				 array_push($error_array, "Invalid Email <br>");
			}
		}
		else {
			array_push($error_array, "Email don't match<br>");
		}

		if(strlen($fname) > 50 || strlen($fname) <2){
			array_push($error_array, "Your First name must be between 2 and 50 characters<br> ");
		}
		if(strlen($lname) > 50 || strlen($lname) <2){
			array_push($error_array, "Your last name must be between 2 and 50 characters<br>");
		}
		if($password != $password2){
			array_push($error_array, "Your password do not match");
		}
		else{
			if(preg_match('/[^A-Za-z0-9]/',$password)){
				array_push($error_array, "Your password can only contain english characters or numbers ");
			}
		}
		if(strlen($password) >30 || strlen($password) <5){
			array_push($error_array, "Your password must be between 6 and 30 characters ");
		}
		if(empty($error_array)){
			$password = md5($password); //Encrypt password

			//Generate username
			$username = strtolower($fname."_".$lname);

			$check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");

			$i = 0;
			//if username exist add number to username
			while(mysqli_num_rows($check_username_query) != 0){
				$i++;
				$username = $username."_".$i;
				$check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
			}
			//default profile pic
			$rand = rand(1,2);
			// if($rand == 1){
			// 	$profile_pic = "assets/images/profile_pics/defaultprofile1.png";
			// } 
			// else if($rand == 2){
			// 	$profile_pic = "assets/images/profile_pics/defaultprofile2.jpg";
			// }
			$rand = rand(1,2);
			$profile_pic = "assets/images/profile_pics/defaultprofile".$rand.".jpg";

			$insertquery = "INSERT INTO users(first_name,last_name,username,email,password,signup_date,profile_pic,num_posts,num_likes,user_closed,friend_array) VALUES('$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')";
			// $query = mysqli_query($conn, $insertquery);
			$conn->query($insertquery);

			array_push($error_array,"<span>You are all set! Goahead and login</span>");

			$_SESSION['reg_fname'] = "";
			$_SESSION['reg_lname'] = "";
			$_SESSION['reg_email'] = "";
			$_SESSION['reg_email2'] = "";
			$_SESSION['username'] = $username;
            header('location:index.php');
			
			
 		}

	}
?>
