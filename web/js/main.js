"use strict";
// UNIX time to People Time
Number.prototype.padLeft = function(base,chr){
    var  len = (String(base || 10).length - String(this).length)+1;
    return len > 0? new Array(len).join(chr || '0')+this : this;
};
function messageTime(unixTime){
    let d = new Date(unixTime * 1000);
    return [(d.getMonth()+1).padLeft(),
            d.getDate().padLeft(),
            d.getFullYear()].join('/') +' ' +
        [d.getHours().padLeft(),
            d.getMinutes().padLeft(),
            d.getSeconds().padLeft()].join(':');
}

var socket = new WebSocket('ws://localhost:8081');
socket.onopen = function(e) {
    $(".alert_none_connection").css("display", "none");
    socket.send('{"command": "subscribe", "channel": ' + userId + '}');
};

// SEND AND GET MESSAGES FROM WEBSOCKET
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
            $("#messages").empty();
            for(let i = 0; i < messages.length; i++) {
                $("#messages").append('<p id="message_'+messages[i].id+'"><span class="message_text">' + messages[i].message + '</span> <span class="time">'+ messageTime( messages[i].updated_at ) +'</span> <span class="edit_message" data-toggle="modal" data-target="#exampleModalCenter" data-id="'+messages[i].id+'">edit</span> <span class="delete_message" data-id="'+messages[i].id+'">delete</span></p>');
            }
        });

        function sendMessage(){
            let file = '';
            if($("#file").val().length > 1){
                file = " <img src='/"+$('#file').val()+"'>";
            }
            socket.send('{"command": "message", "userId":'+userId+',"to":'+$("#chat_with").attr("data-id")+', "message":"'+$("#message").val()+file+'"}');
            $("#message").val("");
        }

        $("#button").click(function(){
            sendMessage();
        });

        $('#message').keydown(function(e) {
            if(e.keyCode === 13) {
                sendMessage();
            }
        });

    socket.onmessage = function(e) {
        let text = JSON.parse(e.data);
        if(text.userId != userId){
            let userName = $("#user_"+text.userId).text();
            alert("New message from "+userName);
        }
        if($("#chat_with").data('id') == text.userId || text.userId == userId) {
            $("#messages").append('<p id="message_' + text.message_id + '"><span class="message_text">' + text.message + '</span> <span class="time">' + messageTime(text.time) + '</span> </span> <span class="edit_message" data-toggle="modal" data-target="#exampleModalCenter" data-id="' + text.message_id + '">edit</span> <span class="delete_message" data-id="' + text.message_id + '">delete</span></p>');
        }
    };
    socket.onerror = function (e) {
        $(".alert_none_connection").css("display", "block");
        console.log(e);
    };
});
// SAVE IMAGES
$('#sortpicture').on('change', function() {
    var file_data = $('#sortpicture').prop('files');
    var form_data = new FormData();
    $.each( file_data, function( key, value ){
        form_data.append( key, value );
    });
    $.ajax({
        url: '/site/save-file',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(php_script_response){
            let images = JSON.parse(php_script_response);
            $.each(images, function(key, value){
                $("#file").val(value);
                $("#button").trigger("click");
                $('#file').val("");
            });
        }
    });
    $('#sortpicture').val("");
    $('#message').val("");
});

// EDIT MESSAGE open Modal
$("#messages").on("click", ".edit_message", function(){
    $("#edit_input").val('');
    $("#edit_id").val('');
    $("#edit_input").val($(this).parent().find(".message_text").text());
    $("#edit_id").val($(this).data('id'));
});
// EDIT MESSAGE QUERY
$("#save_updates").click(function () {
    $.post("/site/edit-message",
        {
            id: $("#edit_id").val(),
            text: $("#edit_input").val(),
            _csrf: yii.getCsrfToken(),
        },
        function (data, status) {
            if (data) {
                let message = JSON.parse(data);
                $("#message_"+message.id).find(".message_text").text(message.message);
                $("#close_modal").trigger('click');
            }
        });
});
//DELETE MESSAGE
$("#messages").on("click", ".delete_message", function(){
    if(confirm("Удалить сообщение")) {
        $.post("/site/delete-message",
            {
                id: $(this).attr('data-id'),
                _csrf: yii.getCsrfToken(),
            },
            function (data, status) {
                if (data) {
                    $("#message_" + data).remove();
                }
            });
    }
});


