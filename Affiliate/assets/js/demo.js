$(document).ready(function(){
    //button for profile post
    $('#submit_profile_post').click(function(){
        
        $.ajax({
            type : "POST",
            url : "includes/handlers/ajax_submit_profile_post.php",
            data : $('.profile_post').serialize(),
            success: function(msg){
                $("#post_form").modal('hide');
                location.reload();
            },
            error: function(){
                alert('Falure');
            }
        });
    });
});
 function getUser(value , user){
     $.post("includes/handlers/ajax_friend_search.php", {query:value,userLoggedIn:user}, function(data){
        $(".results").html(data);
     });
 }