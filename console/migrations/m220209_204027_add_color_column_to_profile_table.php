<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%profile}}`.
 */
class m220209_204027_add_color_column_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profile}}','rate',$this->string()->after('user_id'));
        $this->addColumn('{{%profile}}','color',$this->string()->after('rate'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profile}}','color');
        $this->dropColumn('{{%profile}}','rate');
    }
}
