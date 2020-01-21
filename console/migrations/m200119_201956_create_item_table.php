<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 */
class m200119_201956_create_item_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(20)->notNull(),
            'length' => $this->integer(5)->notNull(),
            'width' => $this->integer(5)->notNull(),
            'height' => $this->integer(5)->notNull(),
            'price' => $this->float()->notNull(),
        ], $tableOptions);

        $this->alterColumn('{{%items}}', 'id', $this->integer(5).' NOT NULL AUTO_INCREMENT');

        $this->insert('{{%items}}', [
                'id' => '1',
                'title' => 'Product A',
                'length' => '2',
                'width' => '4',
                'height' => '1',
                'price' => '99',
        ]);

        $this->insert('{{%items}}', [
                'id' => '2',
                'title' => 'Product B',
                'length' => '1',
                'width' => '1',
                'height' => '2',
                'price' => '49.9',
        ]);

        $this->insert('{{%items}}', [
                'id' => '3',
                'title' => 'Product C',
                'length' => '5',
                'width' => '3',
                'height' => '2',
                'price' => '150',
        ]);

        $this->insert('{{%items}}', [
                'id' => '4',
                'title' => 'Product D',
                'length' => '3',
                'width' => '4',
                'height' => '5',
                'price' => '170',
        ]);

        $this->insert('{{%items}}', [
                'id' => '5',
                'title' => 'Product E',
                'length' => '5',
                'width' => '5',
                'height' => '5',
                'price' => '299.99',
        ]);

        $this->insert('{{%items}}', [
                'id' => '6',
                'title' => 'Product F',
                'length' => '2',
                'width' => '3',
                'height' => '3',
                'price' => '125',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%items}}');
    }
}
