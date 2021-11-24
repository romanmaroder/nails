<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%viber}}`.
 */
class m211121_223929_create_viber_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%viber}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'name'=>$this->string(),
            'viber_user_id'=>$this->string(),
            'primary_device_os'=>$this->string(),
            'api_version'=>$this->integer(),
            'device_type'=>$this->string()
        ]);

        $this->createIndex(
            'fk-viber-user_id',
            'viber',
            'user_id'
        );

        $this->addForeignKey(
            'fk-viber-user_id',
            'viber',
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
        $this->dropForeignKey('fk-viber-user_id', 'viber');

        $this->dropIndex('fk-viber-user_id', 'viber');

        $this->dropTable('{{%viber}}');
    }
}
