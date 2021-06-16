<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo}}`.
 */
class m210607_112626_create_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photo}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'client_id'=>$this->integer(),
            'master_work'=>$this->smallInteger(),
            'portfolio'=>$this->smallInteger()->defaultValue(0),
            'image'=>$this->string(),
            'created_at'=>$this->integer(11),
            'updated_at'=>$this->integer(11),
        ]);


        $this->createIndex(
            'fk-photo-user_id',
            'photo',
            'user_id'
        );

        $this->addForeignKey(
            'fk-photo-user_id',
            'photo',
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
        $this->dropForeignKey('fk-photo-user_id', 'photo');

        $this->dropIndex('fk-photo-user_id', 'photo');

        $this->dropTable('{{%photo}}');
    }
}
