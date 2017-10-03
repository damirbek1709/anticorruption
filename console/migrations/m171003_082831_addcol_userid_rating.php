<?php

use yii\db\Migration;

class m171003_082831_addcol_userid_rating extends Migration
{
    public function safeUp()
    {
        $this->addColumn('rating','user_id','integer(11) NOT NULL');
    }

    public function safeDown()
    {
        echo "m171003_082831_addcol_userid_rating cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171003_082831_addcol_userid_rating cannot be reverted.\n";

        return false;
    }
    */
}
