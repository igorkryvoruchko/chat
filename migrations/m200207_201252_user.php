<?php

use yii\db\Migration;

/**
 * Class m200207_201252_user
 */
class m200207_201252_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(100),
            'password' => $this->string(256),
            'authKey' => $this->string(256),
            'accessToken' => $this->string(256),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200207_201252_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200207_201252_user cannot be reverted.\n";

        return false;
    }
    */
}
