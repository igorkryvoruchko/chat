"use strict";
Number.prototype.padLeft = function(base,chr){
    var  len = (String(base || 10).length - String(this).length)+1;
    return len > 0? new Array(len).join(chr || '0')+this : this;
}
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
                let d = new Date(messages[i].updated_at * 1000);
                let time = [(d.getMonth()+1).padLeft(),
                        d.getDate().padLeft(),
                        d.getFullYear()].join('/') +' ' +
                    [d.getHours().padLeft(),
                        d.getMinutes().padLeft(),
                        d.getSeconds().padLeft()].join(':');
                $("#messages").append('<p>' + messages[i].message + ' <span class="time">'+ time +'</span></p>');
            }
        });
    let socket = new WebSocket('ws://localhost:8081');//помните про порт: он должен совпадать с тем, который использовался при запуске серверной части
    socket.onopen = function(e) {
        $(".alert_none_connection").css("display", "none");
        socket.send('{"command": "subscribe", "channel": '+userId+'}');
        $("#button").click(function(){
            let file = '';
            if($("#file").val().length > 1){
                file = " <img src='/"+$('#file').val()+"'>";
            }
            socket.send('{"command": "message", "userId":'+userId+',"to":'+$("#chat_with").attr("data-id")+', "message":"'+$("#message").val()+file+'"}');
            $("#messages").append('<p>'+$("#message").val()+file+'</p>');
            $("#message").val("");
        });

    };
    socket.onmessage = function(e) {
        console.log(e.data);
        let text = JSON.parse(e.data);
        console.log(text);
        $("#messages").append('<p>'+text.message+'</p>');
    };
    socket.onerror = function (e) {
        $(".alert_none_connection").css("display", "block");
        console.log(e);
    };
});

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
            //console.log(php_script_response);
            let images = JSON.parse(php_script_response);
            $.each(images, function(key, value){
                $("#file").val(value);
                $("#button").trigger("click");
            });
        }
    });
});