<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_category}}`.
 */
class m210623_112450_create_post_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_category}}', [
            'id' => $this->primaryKey(),
            'post_id'=>$this->integer(),
            'category_id'=>$this->integer(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
        ]);

        $this->createIndex(
            'fk-post_category_id',
            'post_category',
            'post_id'
        );

        $this->addForeignKey(
            'fk-post_category_id',
            'post_category',
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
        $this->dropForeignKey('fk-post_category_id', 'post_category');

        $this->dropIndex('fk-post_category_id', 'post_category');

        $this->dropTable('{{%post_category}}');
    }
}
