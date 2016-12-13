$(document).ready(function(){
     $("#related").click(function(){
        $.post("http://localhost:8080",
        {
          related: "true"
        },
        function(){
        });
        alert("post sent");
    });
   $("#unrelated").click(function(){
        $.post("http://localhost:8080",
        {
          related: "false"
        },
        function(){
        });
    });
});
