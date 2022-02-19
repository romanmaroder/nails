<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%profile}}`.
 */
class m220219_105659_drop_rate_column_from_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%profile}}', 'rate');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%profile}}', 'rate', $this->integer());
    }
}
