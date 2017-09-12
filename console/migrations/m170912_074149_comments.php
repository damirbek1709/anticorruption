<?php

use yii\db\Migration;

class m170912_074149_comments extends Migration
{
    public function safeUp()
    {
        /*$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'name' =>$this->string('255')->notNull(),
            'date' =>$this->dateTime(),
            'email' =>$this->string('255')->notNull(),
            'text' =>$this->text(),
            'news_id' => $this->integer('11')->defaultValue(0),
            'category_id' => $this->integer('11')->defaultValue(0),
        ],$tableOptions);*/
        $this->createIndex('idx_com_ctg', 'comments', 'category_id');
        $this->createIndex('idx_com_news', 'comments', 'news_id');
    }

    public function safeDown()
    {
        echo "m170912_074149_comments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170912_074149_comments cannot be reverted.\n";

        return false;
    }
    */
}
