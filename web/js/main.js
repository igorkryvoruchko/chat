"use strict";
$(".user_item").click(function(){
    $("#chat_with").attr("data-id", $(this).data("id"));
    $("#chat_with").css("display", "block");
    $.get("/site/message-history",
        {
            from: $(this).data("self"),
            to: $(this).data("id"),
            _csrf: yii.getCsrfToken(),
        },
        function(data, status){
            let messages = JSON.parse(data);
            console.log(messages);
            $("#messages").empty();
            for(let i = 0; i < messages.length; i++) {
                $("#messages").append('<p>' + messages[i].message + '</p>');
            }
            console.log("Data: " + data + "\nStatus: " + status);
        });

});