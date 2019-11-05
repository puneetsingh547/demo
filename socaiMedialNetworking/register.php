<?php
	require 'config/config.php';
	require 'includes/form_handler/register_handler.php';
	require 'includes/form_handler/login_handler.php';

?>

<html>
	<head>
		<title>welcome to register</title>
		<link rel="stylesheet" href="assets/css/register_style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="assets/js/register.js"></script>
	</head>
	<body>
		<div id="wrapper">
			
			<div class="contant">
				<div class="login-header">
					<h1>Stack Developer</h1>
					<p>Login and sign up blow!</p>
				</div>
				<div id="first">
					<form action="register.php" method="POST">
						<input type="email" placeholder="Email Address" name="log_email"
							value="<?php if(isset($_SESSION['log_email'])) echo $_SESSION['log_email']; ?>"required><br>
						<input type="password" placeholder="Password" name="log_password"><br>
						<input type="submit" name="log_button" value="Login">
						<?php if(in_array("invalid email or password", $error_array)) echo "<br> Incerrect Email or Password" ?>
						<br><a href="#" id="signup" class="signup">Need an account? Register here!</a>
					</form>

				</div>
				<div id="second">
					<form action="register.php" method="post">
						<input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])){ echo $_SESSION['reg_fname']; } ?>" required><br>
						<?php if(in_array("Your First name must be between 2 and 50 characters<br> ", $error_array)) echo "Your First name must be between 2 and 50 characters<br> "; ?>

						<input type="text" name="reg_lname" placeholder="Lirst Name" value="<?php if(isset($_SESSION['reg_lname'])){ echo $_SESSION['reg_lname']; } ?>" required><br>
						<?php if(in_array("Your last name must be between 2 and 50 characters<br>", $error_array)) echo "Your last name must be between 2 and 50 characters<br>"; ?>

						<input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION['reg_email'])){ echo $_SESSION['reg_email']; } ?>" required><br>

						<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php if(isset($_SESSION['reg_email2'])){ echo $_SESSION['reg_email2']; } ?>" required><br>
						<?php if(in_array("Email is already exist <br>", $error_array)) echo "Email is already exist <br>"; 
						else if(in_array("Invalid Email <br>", $error_array)) echo "Invalid Email <br>";
						else if(in_array("Email don't match<br>", $error_array)) echo "Email don't match<br>"; ?>

						<input type="password" name="reg_password" placeholder="Password" required><br>
						<input type="password" name="reg_password2" placeholder="Confirm password" required><br>
						<?php if(in_array("Your password must be between 2 and 30 characters ",$error_array)) echo "Your password must be between 2 and 30 characters <br>";
						else if(in_array("Your password do not match", $error_array)) echo "Your password do not match<br>";
						else if(in_array("Your password can only contain english characters or numbers ", $error_array)) echo "Your password can only contain english characters or numbers<br>";
						?>
						<input type="submit" name="reg_button" value="Register">

						<?php if(in_array("<span>You are all set! Goahead and login</span>", $error_array)) echo "<br><span>You are all set! Goahead and login</span>"; ?>
						<br> <a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>

