<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expenseslist}}`.
 */
class m220204_163848_create_expenseslist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%expenseslist}}',
            [
                'id'          => $this->primaryKey(),
                'expenses_id' => $this->integer(),
                'price'       => $this->integer(),
                'created_at'  => $this->integer(),
                'updated_at'  => $this->integer(),
            ]
        );


        $this->createIndex(
            'fk-expenses-expenses_id',
            'expenseslist',
            'expenses_id'
        );

        $this->addForeignKey(
            'fk-expenses-expenses_id',
            'expenseslist',
            'expenses_id',
            'expenses',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-expenses-expenses_id', 'expenses');

        $this->dropIndex('fk-expenses-expenses_id', 'expenses');

        $this->dropTable('{{%expenseslist}}');
    }
}
