<?php

use yii\db\Migration;

/**
 * Class m200119_211902_insertAdmin
 */
class m200119_211902_insertAdmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    // public function safeUp()
    // {

    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function safeDown()
    // {
    //     echo "m200119_211902_insertAdmin cannot be reverted.\n";

    //     return false;
    // }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->insert('{{%user}}', [
            'id' => '1',
            'username' => 'admin',
            'email' => 'admin@test.app',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('ntcnflvby'),
            'status' => '777',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down()
    {
        echo "m200119_211902_insertAdmin cannot be reverted.\n";

        $this->delete('{{%user}}', ['id' => 1]);

        return false;
    }
    
}
