<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%certificate}}`.
 */
class m210607_093902_create_certificate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificate}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'certificate'=>$this->string()
        ]);

        $this->createIndex(
            'fk-certificate-user_id',
            'certificate',
            'user_id'
        );

        $this->addForeignKey(
            'fk-certificate-user_id',
            'certificate',
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
        $this->dropForeignKey('fk-certificate-user_id', 'certificate');

        $this->dropIndex('fk-certificate-user_id', 'certificate');

        $this->dropTable('{{%certificate}}');
    }
}
