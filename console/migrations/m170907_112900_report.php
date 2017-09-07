<?php

use yii\db\Migration;

class m170907_112900_report extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('report', [
            'id' => $this->primaryKey(),
            'title' =>$this->string('255')->notNull(),
            'date' =>$this->dateTime(),
            'views' => $this->integer('11')->defaultValue(0),
            'author' =>$this->string('255')->notNull(),
            'user_id' => $this->integer('11')->defaultValue(0),
            'authority_id' => $this->integer('11')->defaultValue(0),
            'category_id' => $this->integer('11')->defaultValue(0),
            'lon' => $this->double(),
            'lat' => $this->double(),
            'city_id' => $this->integer('11')->defaultValue(0),
            'text' =>$this->text(),
            'anonymous'=>$this->boolean(),
            'email' =>$this->string('255')->notNull(),
            'contact' =>$this->string('255')->notNull(),
        ],$tableOptions);
        $this->createIndex('idx_report_auth', 'report', 'authority_id');
        $this->createIndex('idx_report_ctg', 'report', 'category_id');
    }

    public function safeDown()
    {
        echo "m170907_112900_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_112900_report cannot be reverted.\n";

        return false;
    }
    */
}
