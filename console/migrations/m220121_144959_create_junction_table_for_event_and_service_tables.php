<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_service}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%event}}`
 * - `{{%service}}`
 */
class m220121_144959_create_junction_table_for_event_and_service_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_service}}', [
            'id'         => $this->primaryKey(),
            'event_id' => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
            /*'PRIMARY KEY(event_id, service_id)',*/
        ]);

        // creates index for column `event_id`
        $this->createIndex(
            '{{%idx-event_service-event_id}}',
            '{{%event_service}}',
            'event_id'
        );

        // add foreign key for table `{{%event}}`
        $this->addForeignKey(
            '{{%fk-event_service-event_id}}',
            '{{%event_service}}',
            'event_id',
            '{{%event}}',
            'id',
            'CASCADE'
        );

        // creates index for column `service_id`
        $this->createIndex(
            '{{%idx-event_service-service_id}}',
            '{{%event_service}}',
            'service_id'
        );

        // add foreign key for table `{{%service}}`
        $this->addForeignKey(
            '{{%fk-event_service-service_id}}',
            '{{%event_service}}',
            'service_id',
            '{{%service}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%event}}`
        $this->dropForeignKey(
            '{{%fk-event_service-event_id}}',
            '{{%event_service}}'
        );

        // drops index for column `event_id`
        $this->dropIndex(
            '{{%idx-event_service-event_id}}',
            '{{%event_service}}'
        );

        // drops foreign key for table `{{%service}}`
        $this->dropForeignKey(
            '{{%fk-event_service-service_id}}',
            '{{%event_service}}'
        );

        // drops index for column `service_id`
        $this->dropIndex(
            '{{%idx-event_service-service_id}}',
            '{{%event_service}}'
        );

        $this->dropTable('{{%event_service}}');
    }
}
