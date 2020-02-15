<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Chat';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-chat">
    <h1>Chat</h1>
    <div id="messages" style="border: 1px solid goldenrod"></div>
    <hr>
    <input type="text" id="message" placeholder="enter message">
    <button id="button">Send</button>
</div>
<script>

    socket = new WebSocket('ws://localhost:8081');//помните про порт: он должен совпадать с тем, который использовался при запуске серверной части
    socket.onopen = function(e) {
        alert("[open] Соединение установлено");
            $("#button").click(function(){
                socket.send('{"idUser":<?=Yii::$app->user->getId()?>, "message":"'+$("#message").val()+'"}');
                $("#messages").append('<p>'+$("#message").val()+'</p>');
                $("#message").val("");
            });

    };
    socket.onmessage = function(e) {
        console.log(e.data);
        let text = JSON.parse(e.data);
        console.log(text);
        $("#messages").append('<p>'+text.message+'</p>');
    };

</script>
