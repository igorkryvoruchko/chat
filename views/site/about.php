<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
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
                socket.send('{"idUser":"igor", "message":"'+$("#message").val()+'"}');
                $("#messages").append('<p>'+$("#message").val()+'</p>');
                $("#message").val("");
            });
             //часть моего кода. Сюда вставлять любой валидный json.
    };
    socket.onmessage = function(e) {
        console.log(e.data);
        let text = JSON.parse(e.data);
        console.log(text);
        $("#messages").append('<p>'+text.message+'</p>');
    };
    // let socket = new WebSocket("wss://localhost:8081");
    //
    // socket.onopen = function(e) {
    //     alert("[open] Соединение установлено");
    //     alert("Отправляем данные на сервер");
    //     socket.send("Меня зовут Джон");
    // };
    //
    // socket.onmessage = function(event) {
    //     alert(`[message] Данные получены с сервера: ${event.data}`);
    // };
    //
    // socket.onclose = function(event) {
    //     if (event.wasClean) {
    //         alert(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
    //     } else {
    //         // например, сервер убил процесс или сеть недоступна
    //         // обычно в этом случае event.code 1006
    //         alert('[close] Соединение прервано');
    //     }
    // };
    //
    // socket.onerror = function(error) {
    //     alert(`[error] ${error.message}`);
    // };
</script>
