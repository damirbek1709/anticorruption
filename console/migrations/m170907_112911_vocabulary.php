<?php

use yii\db\Migration;

class m170907_112911_vocabulary extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('vocabulary', [
            'id' => $this->primaryKey(),
            'key' =>$this->string('255')->notNull(),
            'value' =>$this->string('500')->notNull(),
            'ordered_id' => $this->integer('11')->notNull()->defaultValue(0),
            'parent' => $this->integer('5')->defaultValue(0),
            /*'value_ky' =>$this->string('500')->notNull(),
            'value_en' =>$this->string('500')->notNull(),*/
        ],$tableOptions);
    }

    public function safeDown()
    {
        echo "m170907_112911_vocabulary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_112911_vocabulary cannot be reverted.\n";

        return false;
    }
    */
}
