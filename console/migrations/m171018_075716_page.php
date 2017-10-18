<?php

use yii\db\Migration;

class m171018_075716_page extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('page', [
            'id' => $this->primaryKey(),
            'title' =>$this->string('255')->notNull(),
            'text' =>$this->text(),
        ],$tableOptions);

    }

    public function safeDown()
    {
        echo "m171018_075716_page cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171018_075716_page cannot be reverted.\n";

        return false;
    }
    */
}
