<?php
    if(isset($_POST["login"])){
        $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
        $password = md5($_POST['log_password']);

        $check_login_query = mysqli_query($con , "SELECT * from users where email='$email' and password='$password'");
        if(mysqli_num_rows($check_login_query) == 1 ){
            $row = mysqli_fetch_array($check_login_query);
            $user_account_open = mysqli_query($con , "SELECT * from users where email='$email' and user_closed='yes'");
            if(mysqli_num_rows($user_account_open) == 1){
                $reopen_account = mysqli_query($con , "UPDATE users set user_closed='no' where email='$email' ");
            }
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $email;
            header("location: index.php");
        }
        else{
            array_push($error_array, "Incorrect Email or Password<br>");
        }
    }
?>