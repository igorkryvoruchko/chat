<?php

use yii\db\Migration;

/**
 * Class m200216_061401_message
 */
class m200216_061401_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('message', [
            'id' => $this->primaryKey(),
            'from' => $this->integer(),
            'to' => $this->integer(),
            'message' => $this->string(1000),
            'created_at' => $this->integer()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->notNull()->comment('Updated At'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200216_061401_message cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200216_061401_message cannot be reverted.\n";

        return false;
    }
    */
}
