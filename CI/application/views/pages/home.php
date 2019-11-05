<div class='form'>
    <input type="text" id='name' placeholder='Name...'value='Puneet'><br>
    <input type="text" id="email" placeholder='Email...' value="Puneet@puneet.com"><br>
    <input type="text" id="num" placeholder="Number..."  value='9650552124'><br>
    <button id='submit'>Submit</button><br>

</div>
<script>
$(document).ready(function(){
    $("#submit").click(function(){
        var name = "Puneet choudhary";
         $.ajax({
            url : "<?php echo site_url('Pages/send'); ?>",
            data : {name : $("#name").val(), email : $("#email").val(), number: $("#num").val()},
            type : "POST",
            success : function(res){
                $.ajax({
                    url : '<?php echo site_url('Pages/show'); ?>',
                    dtatType : "json",
                    type : "post",
                    success : function(data){
                        obj = JSON.parse(data);
                        console.log(obj['name']);
                    }
                    });
            },
            error : function(res){
                console.log(res);
            }
         });
    });
});
</script>