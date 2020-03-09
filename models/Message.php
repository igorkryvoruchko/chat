<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int|null $from
 * @property int|null $to
 * @property string|null $message
 * @property int $created_at Created At
 * @property int $updated_at Updated At
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'message' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function saveMessage($message)
    {
        $newMessage = new self();
        $newMessage->from = $message->userId;
        $newMessage->to = $message->to;
        $newMessage->message = $message->message;
        $newMessage->save();

        return ['message_id' => $newMessage->id, 'time' => $newMessage->updated_at];
    }
}
