<?php

use yii\db\Migration;

class m171027_074756_adcol_comment extends Migration
{
    public function safeUp()
    {
        $this->addColumn('comments','user_id','integer(11) NOT NULL');
        $this->addColumn('comments','status','tinyint(1) NOT NULL');
    }

    public function safeDown()
    {
        echo "m171027_074756_adcol_comment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171027_074756_adcol_comment cannot be reverted.\n";

        return false;
    }
    */
}
