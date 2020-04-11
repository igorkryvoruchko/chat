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
    <input id="sortpicture" type="file" multiple name="sortpic[]" />
    <input type="text" id="message" placeholder="enter message">
    <input type="hidden" id="file">
    <button id="button" class="btn btn-success btn-sm">Send</button>
    <hr>
</div>

<div class="user_list">
    <h3>Users</h3>
    <ul>
        <?php foreach ($users as $user){?>
            <li class="user_item" id="user_<?=$user->id?>" data-self="<?=Yii::$app->user->getId()?>" data-id="<?=$user->id?>"><?=$user->username?></li>
        <?php } ?>
    </ul>
</div>
<script>
    var userId = <?=Yii::$app->user->getId()?>;
</script>
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="edit_input">
                <input type="hidden" id="edit_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_updates">Save changes</button>
            </div>
        </div>
    </div>
</div>