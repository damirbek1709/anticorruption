<?php

use yii\db\Migration;

class m170907_112852_news extends Migration
{
    public function safeUp()
    {
        /*$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' =>$this->string('255')->notNull(),
            'description' =>$this->string('255')->notNull(),
            'text' =>$this->text(),
            'img' =>$this->string('255')->notNull(),
            'views' => $this->integer('11')->defaultValue(0),
            'category_id' => $this->integer('11')->defaultValue(0),
            'date' =>$this->dateTime(),
            'main_news'=>$this->boolean(),
        ],$tableOptions);*/
        $this->createIndex('idx_authority_ctg', 'news', 'category_id');
        $this->createIndex('idx_authority_mainnews', 'news', 'main_news');
    }

    public function safeDown()
    {
        echo "m170907_112852_news cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_112852_news cannot be reverted.\n";

        return false;
    }
    */
}
