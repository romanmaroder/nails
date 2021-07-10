<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_image}}`.
 */
class m210707_090126_create_post_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_image}}', [
            'id' => $this->primaryKey(),
            'post_id'=>$this->integer()->defaultValue(null),
            'image'=>$this->string()
        ]);

        $this->createIndex(
            'fk-image-post_id',
            'post_image',
            'post_id'
        );

        $this->addForeignKey(
            'fk-image-post_id',
            'post_image',
            'post_id',
            'post',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-image-post_id', 'post_image');

        $this->dropIndex('fk-image-post_id', 'post_image');

        $this->dropTable('{{%post_image}}');
    }
}
