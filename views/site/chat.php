<?php

/* @var $this yii\web\View */
/** @var TYPE_NAME $users */

use yii\helpers\Html;

$this->title = 'Chat';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site_chat" id="chat_with" data-id="" style="display:none">
    <h1>Chat</h1>
    <div id="messages" style="border: 1px solid goldenrod"></div>
    <hr>
    <input type="text" id="message" placeholder="enter message">
    <button id="button">Send</button>
    <hr>
</div>
<div class="user_list">
    <h3>Users</h3>
    <ul>
        <?php foreach ($users as $user){?>
            <li class="user_item" data-self="<?=Yii::$app->user->getId()?>" data-id="<?=$user->id?>"><?=$user->username?></li>
        <?php } ?>
    </ul>
</div>
<script>

    socket = new WebSocket('ws://localhost:8081');//помните про порт: он должен совпадать с тем, который использовался при запуске серверной части
    socket.onopen = function(e) {
        alert("[open] Соединение установлено");
            $("#button").click(function(){
                socket.send('{"userId":<?=Yii::$app->user->getId()?>,"to":'+$("#chat_with").attr("data-id")+', "message":"'+$("#message").val()+'"}');
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
