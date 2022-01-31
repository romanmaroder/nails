<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service}}`.
 */
class m211225_111532_create_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%service}}',
            [
                'id'         => $this->primaryKey(),
                'name'       => $this->string(),
                'cost'       => $this->integer(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%service}}');
    }
}
