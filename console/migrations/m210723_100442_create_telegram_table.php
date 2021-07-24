<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram}}`.
 */
class m210723_100442_create_telegram_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%telegram}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'username'=>$this->string(),
            'chat_id'=>$this->integer(),
            'name'=>$this->string()
        ]);
        $this->createIndex(
            'fk-telegram-user_id',
            'telegram',
            'user_id'
        );

        $this->addForeignKey(
            'fk-telegram-user_id',
            'telegram',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-telegram-user_id', 'telegram');

        $this->dropIndex('fk-telegram-user_id', 'telegram');

        $this->dropTable('{{%telegram}}');
    }
}
