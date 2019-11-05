<?php
    include "config/config.php";
    include "includes/form_handler/register_handler.php";
    include "includes/form_handler/login_handler.php"

?>
<html>
    <head>
        <title>Demo</title>
        <link rel="stylesheet" href="assets/css/register_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="assets/js/register.js"></script>
    </head>
    <body>
        <?php
        if(isset($_POST['register_button'])){
        echo '<script>
            $(document).ready(function(){
                $("#first").hide();
                $("#second").show();
            });
        </script>';
        }
        ?>

        <div class="wrapper">
            <div class="login_box">
                <div class="login_header">
                    <h1> Choudhary</h1>
                    login and signup blow!
                </div>
                <div id="first">
                    <form action="register.php" method="POST">
                        <input type="email" name="log_email" placeholder="Email Address" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; ?>" required><br>
                        <input type="password" name="log_password" placeholder="Password" required><br>
                        <input type="submit" name="login" value="Login"><br>
                        <?php if(in_array("Incorrect Email or Password<br>", $error_array)) echo "Incorrect Email or Password<br>"; ?>
                        <a href="#" class="signup" id="signup">Need an account? Register here!</a>
                    </form>

                </div>
                <div id="second">

                    <form action="register.php" method="POST">
                        <input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION["reg_fname"])){
                            echo $_SESSION["reg_fname"];
                        } ?>" required><br>
                        <?php if(in_array("Your first name must be between 2 to 25 characters<br>", $error_array)) echo "Your first name must be between 2 to 25 characters<br>"; ?>

                        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php if(isset($_SESSION["reg_lname"])){
                            echo $_SESSION["reg_lname"];
                        } ?>" required><br>
                        <?php if(in_array("Your last name must be between 2 to 25 characters<br>", $error_array)) echo "Your lirst name must be between 2 to 25 characters<br>"; ?>
                        <input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION["reg_email"])){
                            echo $_SESSION["reg_email"];
                        } ?>" required><br>
                        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php if(isset($_SESSION["reg_email2"])){
                            echo $_SESSION["reg_email2"];
                        } ?>" required><br>
                        <?php if(in_array("Email Don't match<br>", $error_array)) echo "Email Don't match<br>";
                        else if(in_array("Email already in use<br>" , $error_array)) echo "Email already in use<br>";
                        else if(in_array("Invalid email format<br>" , $error_array)) echo "Invalid email format<br>"; ?>

                        <input type="password" name="reg_password" placeholder="Password" required><br>
                        <?php if(in_array("Your password do not match<br>", $error_array)) echo "Your password do not match<br>";
                        else if (in_array("Your password can contain only elglish latter and characters<br>", $error_array)) echo "Your password can contain only elglish latter and characters<br>";
                        else if(in_array("Your password must be between 5 to 30 characters<br>", $error_array)) echo "Your password must be between 5 to 30 characters<br>";
                        ?>
                        

                        <input type="password" name="reg_password2" placeholder="Confirm Password" required><br>
                        <input type="submit" name="register_button" value="Register"><br>
                        <?php if(in_array("<span>You are all set! Goahead and login</span>", $error_array)) echo "<span>You are all set! Goahead and login</span>"; ?>
                        <a href="#" class="signin" id="sighin">Already have an account? Sign in here!</a>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>