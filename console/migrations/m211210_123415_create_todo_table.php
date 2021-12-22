<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%todo}}`.
 */
class m211210_123415_create_todo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%todo}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'title'=>$this->string(),
            'status'=>$this->integer()->defaultValue(0),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
        ]);


        $this->createIndex(
            'fk-todo-user_id',
            'todo',
            'user_id'
        );

        $this->addForeignKey(
            'fk-todo-user_id',
            'todo',
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
        $this->dropForeignKey('fk-todo-user_id', 'todo');

        $this->dropIndex('fk-todo-user_id', 'todo');

        $this->dropTable('{{%todo}}');
    }
}
