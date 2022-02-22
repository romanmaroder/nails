<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%archive}}`.
 */
class m220222_104212_create_archive_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%archive}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'service_id'=>$this->integer(),
            'amount'=>$this->integer(),
            'date'=>$this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-archive-user_id}}',
            '{{%archive}}',
            'user_id',
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-archive-user_id}}',
            '{{%archive}}',
            'user_id',
            '{{%user}}',
            'id'
        );

        // creates index for column `service_id`
        $this->createIndex(
            '{{%idx-archive-service_id}}',
            '{{%archive}}',
            'service_id'
        );

        // add foreign key for table `{{%service}}`
        $this->addForeignKey(
            '{{%fk-archive-service_id}}',
            '{{%archive}}',
            'service_id',
            '{{%service}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%service}}`
        $this->dropForeignKey(
            '{{%fk-archive-service_id}}',
            '{{%archive}}'
        );

        // drops index for column `service_id`
        $this->dropIndex(
            '{{%idx-archive-service_id}}',
            '{{%archive}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-archive-user_id}}',
            '{{%archive}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-archive-user_id}}',
            '{{%archive}}'
        );

        $this->dropTable('{{%archive}}');
    }
}
