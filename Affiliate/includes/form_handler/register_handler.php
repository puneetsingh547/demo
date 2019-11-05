<?php
 $fname = "";
 $lname = "";
 $em = "";
 $em2 = "";
 $password = "";
 $password2 = "";
 $date = "";
 $username = "";
 $error_array = array();

if(isset($_POST["register_button"])){
   

    $fname = strip_tags($_POST["reg_fname"]);
    $fname = str_replace(" ", "", $fname);
    $fname = ucfirst(strtolower($fname));
   $_SESSION["reg_fname"] = $fname;

    $lname = strip_tags($_POST["reg_lname"]);
    $lname = str_replace(" ", "", $lname);
    $lname = ucfirst(strtolower($lname));
    $_SESSION["reg_lname"] = $lname;

   $em = strip_tags($_POST["reg_email"]);
   $em = str_replace(" ", "" , $em);
   $em = ucfirst(strtolower($em));
   $_SESSION["reg_email"] = $em;

   $em2 = strip_tags($_POST["reg_email2"]);
   $em2 = str_replace(" ", "" , $em2);
   $em2 = ucfirst(strtolower($em2));
   $_SESSION["reg_email2"] = $em2;

   $password = strip_tags($_POST["reg_password"]);
   $password2 = strip_tags($_POST["reg_password2"]);

   $date = date("Y-m-d");

   if($em == $em2){
       if(filter_var($em , FILTER_VALIDATE_EMAIL)){
           $em = filter_var($em , FILTER_VALIDATE_EMAIL);

           $e_check = mysqli_query($con , "SELECT email FROM users WHERE email = '$em'");
           $num_email = mysqli_num_rows($e_check);

           if($num_email > 0){
               array_push($error_array ,"Email already in use<br>"); 
           }
       }
       else {
           array_push($error_array, "Invalid email format<br>");
       }
   }
   else {
       array_push($error_array, "Email Don't match<br>");
   }

   if(strlen($fname) > 25 || strlen($fname) < 2){
       array_push($error_array,"Your first name must be between 2 to 25 characters<br>");
   } 
   if(strlen($lname) > 25 || strlen($lname) < 2){
       array_push($error_array, "Your last name must be between 2 to 25 characters<br>");
   }
   if($password != $password2){
       array_push($error_array, "Your password do not match<br>");
   }
   else{
       if(preg_match('/[^A-Za-z0-9]/', $password)){
           array_push($error_array, "Your password can contain only elglish latter and characters<br>");
       }
   }
   if(strlen($password) >30 || strlen($password) < 5){
       array_push($error_array, "Your password must be between 5 to 30 characters<br>");
   }
   if(empty($error_array)){

       $password = md5($password);

       $username = strtolower($fname."_".$lname);

       $check_username = mysqli_query($con , "SELECT username from users where username='$username'");
       $i = 0;
       while(mysqli_num_rows($check_username)){
           $i++;
           $username = $username . "_" . $i;
           $check_username = mysqli_query($con , "SELECT username from users where username='$username'");
           
       }
       $profile_pic = "assets/images/profile_pic/default/defaultprofile1.png";
       
       $query = mysqli_query($con, "INSERT INTO users(first_name,last_name,username,email,password,signup_date,profile_pic,num_posts,num_likes,user_closed,friend_array) VALUES ('$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");
       
       array_push($error_array,"<span>You are all set! Goahead and login</span>");

       $_SESSION['reg_fname'] = "";
       $_SESSION['reg_lname'] = "";
       $_SESSION['reg_email'] = "";
       $_SESSION['reg_email2'] = "";
       $_SESSION['username'] = $username;
   }
}

?>