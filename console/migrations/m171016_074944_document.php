<?php

use yii\db\Migration;

class m171016_074944_document extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'title' =>$this->string('255')->notNull(),
            'text' =>$this->text(),
            'category_id' => $this->integer('10')->notNull(),
            'date' => $this->date()->notNull(),
        ],$tableOptions);

    }

    public function safeDown()
    {
        echo "m171016_074944_document cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171016_074944_document cannot be reverted.\n";

        return false;
    }
    */
}
