<?php
    require "config/config.php";
    include("includes/classes/User.php");
    include("includes/classes/Post.php");
    include("includes/classes/Messages.php");
?>
<?php
    if(isset($_SESSION['username'])){
        $userLoggedIn = $_SESSION['username'];
        
        $get_user_detals = mysqli_query($con , "SELECT * FROM users where username='$userLoggedIn'");
        $user = mysqli_fetch_array($get_user_detals);
    }
    else{
        header("location: register.php");
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Demo</title>
    <!-- js -->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src="assets/js/demo.js"></script>

    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>

    <div class="top_bar">
        <div class="logo">
            <a href="index.php">Choudhary</a>
        </div>
        <nav>
            <a href="<?php echo $userLoggedIn; ?>">
                <?php echo $user['first_name']; ?>
            </a>
            <a href="#">
                <i class="fa fa-home fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-envelope fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-bell-o fa-lg"></i>
            </a>
            <a href="request.php">
            <i class="fa fa-users fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-cog fa-lg"></i>
            </a>
            <a href="includes/handlers/logout.php">
                <i class="fa fa-sign-out fa-lg"></i>
            </a>
        </nav>
    </div>
    <div class=wrapper>