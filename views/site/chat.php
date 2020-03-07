<?php

/* @var $this yii\web\View */
/** @var TYPE_NAME $users */

use yii\helpers\Html;

$this->title = 'Chat';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert alert-danger alert_none_connection alert-dismissible" style="display: none">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Внимание!</strong> Соединение не установленно, проверьте подключение к сети интернет или пользуйтесь Вайбером (шутка)
</div>
<h1>Chat</h1>
<div class="site_chat" id="chat_with" data-id="" style="display:none">
    <div id="messages" class="chat-page" style=""></div>
    <!-- <hr> -->
    <input id="sortpicture" type="file" multiple name="sortpic[]" />
<!--    <button id="upload">Upload</button>-->
    <input type="text" id="message" placeholder="enter message">
    <input type="hidden" id="file">
    <button id="button" class="btn btn-success btn-sm">Send</button>
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
    var userId = <?=Yii::$app->user->getId()?>;
</script>
