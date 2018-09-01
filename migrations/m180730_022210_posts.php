<?php

use yii\db\Migration;

/**
 * Class m180730_022210_posts
 */
class m180730_022210_posts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ('mysql' === $this->db->driverName) {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'author' => $this->string()->notNull(),
            'content'=> $this->text(),
            'status' => $this->integer(1)->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m180730_022210_posts cannot be reverted.\n";
        return false;
    }


    /**
     * {@inheritdoc}
     */
//    public function safeUp()
//    {
//
//    }

    /**
     * {@inheritdoc}
     */
//    public function safeDown()
//    {
//        echo "m180730_022210_posts cannot be reverted.\n";
//
//        return false;
//    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180730_022210_posts cannot be reverted.\n";

        return false;
    }
    */
}
