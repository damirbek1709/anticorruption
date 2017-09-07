<?php

use yii\db\Migration;

class m170907_112744_category extends Migration
{
    public function safeUp()
    {
        /*$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'title' =>$this->string('255')->notNull(),
            'priority' => $this->integer('11')->defaultValue(0),
        ],$tableOptions);*/
    }

    public function safeDown()
    {
        echo "m170907_112744_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_112744_category cannot be reverted.\n";

        return false;
    }
    */
}
