<?php

use yii\db\Migration;

class m170907_125617_add_col_vocabulary_divider extends Migration
{
    public function safeUp()
    {
        $this->addColumn('vocabulary','divider','varchar(255) NULL');

        $this->update('vocabulary',array(
            'divider'=>'Города',
        ),['key'=>'city']);
    }

    public function safeDown()
    {
        echo "m170907_125617_add_col_vocabulary_divider cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_125617_add_col_vocabulary_divider cannot be reverted.\n";

        return false;
    }
    */
}
