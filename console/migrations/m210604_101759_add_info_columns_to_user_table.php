<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m210604_101759_add_info_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'description', $this->text()->defaultValue(null)->after('avatar'));
        $this->addColumn('{{%user}}', 'birthday', $this->string()->defaultValue(null)->after('description'));
        $this->addColumn('{{%user}}', 'phone', $this->string()->defaultValue(null)->after('birthday'));
        $this->addColumn('{{%user}}', 'address', $this->string()->defaultValue(null)->after('phone'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'description');
        $this->dropColumn('{{%user}}', 'birthday');
        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'address');
    }
}
