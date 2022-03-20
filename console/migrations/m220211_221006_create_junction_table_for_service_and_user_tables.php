<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%service}}`
 * - `{{%user}}`
 */
class m220211_221006_create_junction_table_for_service_and_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%service_user}}',
            [
                'id' => $this->primaryKey(),
                'service_id' => $this->integer(),
                'user_id' => $this->integer(),
                'rate' => $this->integer(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                //'PRIMARY KEY(service_id, user_id)',
            ]
        );

        // creates index for column `service_id`
        $this->createIndex(
            '{{%idx-service_user-service_id}}',
            '{{%service_user}}',
            'service_id'
        );

        // add foreign key for table `{{%service}}`
        $this->addForeignKey(
            '{{%fk-service_user-service_id}}',
            '{{%service_user}}',
            'service_id',
            '{{%service}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-service_user-user_id}}',
            '{{%service_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-service_user-user_id}}',
            '{{%service_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%service}}`
        $this->dropForeignKey(
            '{{%fk-service_user-service_id}}',
            '{{%service_user}}'
        );

        // drops index for column `service_id`
        $this->dropIndex(
            '{{%idx-service_user-service_id}}',
            '{{%service_user}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-service_user-user_id}}',
            '{{%service_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-service_user-user_id}}',
            '{{%service_user}}'
        );

        $this->dropTable('{{%service_user}}');
    }
}
