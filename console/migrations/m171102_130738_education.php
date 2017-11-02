<?php

use yii\db\Migration;

class m171102_130738_education extends Migration
{
    public function safeUp()
    {
        /*$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('education', [
            'id' => $this->primaryKey(),
            'title' =>$this->string('255')->notNull(),
            'text' =>$this->text(),
            'category_id' => $this->integer('10')->notNull(),
            'date' => $this->date()->notNull(),
            'title_ky' =>$this->string('255')->notNull(),
            'text_ky' =>$this->text(),
            'title_en' =>$this->string('255')->notNull(),
            'text_en' =>$this->text(),
        ],$tableOptions);*/

    }

    public function safeDown()
    {
        echo "m171102_130738_education cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_130738_education cannot be reverted.\n";

        return false;
    }
    */
}
