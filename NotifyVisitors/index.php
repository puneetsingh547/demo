<?php
session_start();


$_SESSION["count_load_page"] = $_COOKIE['load'];

$hero = "";
    if (!isset($_COOKIE['load']))
        $_COOKIE['load'] = 0;
    
    $visits = $_COOKIE['visits'] + 1;
    setcookie('visits', $visits, time()+3600*24*365);

echo "PHP Page reload : ". $visits ."<br>";

echo "PHP Page reload : ". $_SESSION["count_load_page"];

// if(isset($_COOKIE['load'])){
//     $_SESSION["count_load_page"]++ ;
// }
// echo  "Sessopn Load : ". $_SESSION["count_load_page"] ."<br> Private Variable : ". $loadPage;

if(isset($_GET["hero"])){
    $hero = $_GET["hero"];
}


?>


<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    
    <form action="index.php?hero=" id="myForm" method="GET">
        Name&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="name" name="name" vlaue="<?php echo $hero; ?>"><br>
        Number <input type="number" id="num" name="num"><br>
        <input type="submit" onclick="myFunction()" value="click"><br>
    </form>
<div id="demo"></div>
</body>

</html>

<script>
function myFunction() {
  var x = document.getElementById("myForm").elements[0].value;
  var y = document.getElementById("myForm").elements[1].value;
  var z = document.getElementById("myForm").elements['hero'].value;
  alert("My Name is : " + x + "\n Ny Number : "+y +"\n Hero : "+z );
}


var state = history.state || {};
var reloadCount = state.reloadCount || 0;
if (performance.navigation.type === 1) { // Reload
    state.reloadCount = ++reloadCount;
    history.replaceState(state, null, document.URL);
} else if (reloadCount) {
    delete state.reloadCount;
    reloadCount = 0;
    history.replaceState(state, null, document.URL);
}
// if (reloadCount > 0) {
//     document.getElementById('demo').innerHTML = reloadCount;
// }

document.cookie="load="+reloadCount;    //load cookie in load

$(document).ready(function(){
    
});

</script>
    