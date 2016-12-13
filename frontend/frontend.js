$(document).ready(function(){
     $("#related").click(function(){
        $.post("http://localhost:8080/dummy_backend.php",
        {
          related: "true"
        },
        function(){
        });
        //alert("post sent");
    });
   $("#unrelated").click(function(){
        $.post("http://localhost:8080/dummy_backend.php",
        {
          related: "false"
        },
        function(){
        });
    });
});
