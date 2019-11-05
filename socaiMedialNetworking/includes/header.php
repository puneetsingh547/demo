<?php
    require 'config/config.php';
    include("classes/Message.php");
    include("classes/User.php");
    include("classes/Post.php");
    include("classes/Notification.php");

    if(isset($_SESSION['username'])){
        $userLogeedIn = $_SESSION['username'];
        $user_detail_query = mysqli_query($conn, "SELECT * FROM users where username='$userLogeedIn'");
        $user = mysqli_fetch_array($user_detail_query);
    }
    else{
        header("location: register.php");
    }
?>
<html>
    <head>
        <title>Welcome to Stuck Developer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/bootbox.min.js"></script>
        <script src="assets/js/demo.js"></script>

        <link rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
    <?php
    // unread messages
    $message = new Message($conn, $userLogeedIn);
    $numUnreadMessages = $message->getUnreadMessages();

    // unread notification
    $notification = new Notification($conn, $userLogeedIn);
    $numUnreadNotification = $notification->getUnreadMessages();

    // number of friends requests
    $user_obj = new User($conn, $userLogeedIn);
    $num_requests = $user_obj->getNumberOfFriendRequests();
    ?>
        <div class="top-bar">
            <div class="logo">
                <a href="index.php">Stuck Developer</a>
            </div>

            <div class="search">
                <form action="search.php" method="GET" name="search_form">
                    <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLogeedIn; ?>')" name="q" placeholder="search..."  autocomplete="off" id="search_text_input">
                    <div class="button_holder">
                        <img src="assets/images/icons/search.png" alt="">
                    </div>
                </form>

                <div class="search_results"></div>
                <div class="search_results_footer_empty"></div>
                <div class="search_results_footer" ></div>                
            </div>

            <nav class="nav">
                <a href="<?php echo $user['username']; ?>"><?php echo $user['first_name']; ?></a>
                <a href="index.php">
                    <i class="fa fa-home fa-lg"></i>
                </a>
                <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLogeedIn; ?>', 'message')">
                <i class="fa fa-envelope fa-lg"></i>
                <?php
                if($numUnreadMessages >0)
                echo "<span class='notification_badge' id='unread_message'>$numUnreadMessages</span>";
                ?>
                </a>
                <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLogeedIn; ?>', 'notification')">
                <i class="fa fa-bell-o fa-lg"></i>
                <?php
                if($numUnreadNotification >0)
                echo "<span class='notification_badge' id='unread_notification'>$numUnreadNotification</span>";
                ?>
                </a>
                <a href="request.php"><i class="fa fa-users fa-lg"></i>
                <?php
                if($num_requests >0)
                echo "<span class='notification_badge' id='unread_notification'>$num_requests</span>";
                ?>
                </a>
                <a href="settings.php"><i class="fa fa-cog fa-lg"></i></a>
                <a href="includes/handler/logout.php"><i class="fa fa-sign-out fa-lg"></i></a>

            </nav>
            <div class="dropdown_data_window" style="height:0px;"></div>
            <input type="hidden" id="dropdown_data_type" value="">
        </div>

        <script>
            var userLoggedIn = '<?php echo $userLogeedIn; ?>';
            $(document).ready(function(){
                $(".dropdown_data_window").scroll(function(){

                    var inner_height = $(".dropdown_data_window").innerHeight();
                    var scroll_top = $(".dropdown_data_window").scrollTop();
                    var page_dropdown = $(".dropdown_data_window").find(".nextPageDropdownData").val();
                    var no_more_dropdown = $(".dropdown_data_window").find(".noMorePostDropdownData").val();

                    if((inner_height + scroll_top >= $(".dropdown_data_window")[0].scrollHeight) && no_more_dropdown == 'false'){

                        var pageName;
                        var dropdown_type = $(".dropdown_data_window").val();
                        if(dropdown_type == 'notification')
                            pageName = "ajax_load_notification.php";

                        else if(dropdown_type == 'message')
                            pageName = "ajax_load_messages.php";
                        else
                            pageName = "";

                        var ajaxReq = $.ajax({
                            url: "includes/handler/" + pageName,
                            type:"POST",
                            data: "page="+page_dropdown+"&userLoggedIn="+userLoggedIn,
                            cache: false,
                            success: function(response){
                                $(".dropdown_data_window").find(".nextPageDropdownData").remove();
                                $(".dropdown_data_window").find(".noMorePostDropdownData").remove();

                                $(".dropdown_data_window").append(response);
                            }

                        });
                    }

                });
            });
        </script>

    <div class="wrapper">
