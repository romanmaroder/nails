<?php

use common\models\Profile;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%profile}}`.
 */
class m210607_093904_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profile}}', [

                'id' => $this->primaryKey(),
                'user_id'=>$this->integer(),
                'rate'=>$this->integer(),
                'education'=>$this->text(),
                'notes'=>$this->text(),
                'skill'=>$this->text(),
                'color'=>$this->string()->defaultValue(null),
                'certificate_id'=>$this->smallInteger(),
        ]);
        $this->createIndex(
            'fk-profile-user_id',
            'profile',
            'user_id'
        );

        $this->addForeignKey(
            'fk-profile-user_id',
            'profile',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );


        $admin                = new Profile();
        $admin->user_id = 1;
        $admin->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-profile-user_id', 'profile');

        $this->dropIndex('fk-profile-user_id', 'profile');

        $this->dropTable('{{%profile}}');
    }
}
