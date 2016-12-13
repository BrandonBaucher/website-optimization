$(document).ready(function(){
     $("#related").click(function(){
        $.post("http://localhost:8080/",
        {
          'action': 'my_action',
          'related': "true"
          'individual': 0,//update code for Id
        },
        function(){
        });
        //alert("post sent");
    });
   $("#unrelated").click(function(){
        $.post("http://localhost:8080/",
        {
          'action': 'my_action',
          'related': "false"
          'individual': 0,//update code for Id
        },
        function(){
        });
    });
});
