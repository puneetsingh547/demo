$(document).ready(function(){

    $("#search_text_input").focus(function(){
        if(window.matchMedia("(min-width:800px)" ).matches){
            $(this).animate({width:'250px'},500);
        }
    });

    $('.button_holder').on('click', function(){
        document.search_from.submit();
    });

    // button for submit button
    $('#submit_profile_post').click(function(){

        $.ajax({
            type : "POST",
            url : "includes/handler/ajax_submit_profile_post.php",
            data : $('form.profile_post').serialize(),
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
    // search
    $(document).click(function(e){
        if(e.target.class != "search_results" && e.target.id != "search_text_input"){
            $('.search_results').html("");
            $('.search_results_footer').html("");
            $(".search_results_footer").toggleClass("search_results_footer_empty");
            $('.search_results_footer').toggleClass("search_results_footer")
        }
        if(e.target.class != "dropdown_data_window"){
            $(".dropdown_data_window").html("");
            $(".dropdown_data_window").css({"margin":"0px","height":"0px", "padding":"0px"});
        }
    });

function getLiveSearchUsers(value, user){
    $.post("includes/handler/ajax_search.php",{query:value, userLoggedIn:user}, 
        function(data){
            if($(".search_results_footer_empty")[0]){
                $(".search_results_footer_empty").toggleClass("search_results_footer");
                $(".search_results_footer_empty").toggleClass("search_results_footer");
        }
        $('.search_results').html(data);
        $('.search_results_footer').html("<a href='search.php?q="+value+"'>See All Results</a>");

        if(data = ""){
            $('.search_results_footer').html("");
            $(".search_results_footer").toggleClass("search_results_footer_empty");
            $('.search_results_footer').toggleClass("search_results_footer")
        }
    });
}

function getUsers(value, user){
    $.post("includes/handler/ajax_friend_search.php", {query:value, userLoggedIn:user},function(data){
        $(".results").html(data);
    });
}

function getDropdownData(user, type){
    if($(".dropdown_data_window").css("height") == "0px"){
        var pageName;
    
        if(type =='notification'){
            pageName = "ajax_load_notification.php";
            $("span").remove("#unread_notification");
        }
        
        else if(type == 'message'){
            pageName = "ajax_load_messages.php";
            $("span").remove("#unread_message");
        }
        var ajaxreq = $.ajax({
            url:"includes/handler/"+pageName,
            type: "POST",
            data:"page=1&userLoggedIn=" +user,
            cache:false,

            success: function(response){
                $(".dropdown_data_window").html(response);
                $(".dropdown_data_window").css({"padding":"0px","height":"280px"});
                $(".dropdown_data_window").val(type);
            }
        });
    }
    else{
        $(".dropdown_data_window").html("");
        $(".dropdown_data_window").css({"padding":"0px","height":"0px"});
    }

}