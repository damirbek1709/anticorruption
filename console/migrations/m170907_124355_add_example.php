<?php

use yii\db\Migration;

class m170907_124355_add_example extends Migration
{
    public function safeUp()
    {
        /*$this->addColumn('table_name','row_name','varchar(255) NULL');
        $this->addColumn('table_name','views','integer(11) NOT NULL');
        $this->addColumn('table_name','own','boolean NOT NULL');
        $this->addColumn('table_name','content','text');

        $this->insert('table_name',array(
            'title'=>'the title',
            'description'=>'some description'
        ));*/
    }

    public function safeDown()
    {
        echo "m170907_124355_add_example cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_124355_add_example cannot be reverted.\n";

        return false;
    }
    */
}
