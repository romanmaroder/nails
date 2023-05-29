<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m210512_180508_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%event}}',
            [
                'id'               => $this->primaryKey(),
                'client_id'        => $this->integer(),
                'master_id'        => $this->integer(),
                'description'      => $this->text(),
                'notice'           => $this->string(),
                'event_time_start' => $this->dateTime(),
                'event_time_end'   => $this->dateTime(),
            ]
        );


        $this->createIndex(
            'fk-event-client_id',
            'event',
            'client_id'
        );
        $this->addForeignKey(
            'fk-event-client_id',
            'event',
            'client_id',
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

        $this->dropForeignKey('fk-event-client_id', 'event');

        $this->dropIndex('fk-event-client_id', 'event');

        $this->dropTable('{{%event}}');
    }
}
