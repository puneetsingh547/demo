<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Notification.php");

$limit = 7; //number of message to load

$notification = new Notification($conn, $_REQUEST['userLoggedIn']);
echo $notification->getNotification($_REQUEST, $limit);
?>