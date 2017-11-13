<?php

use yii\db\Migration;

class m171102_123946_voc_lang extends Migration
{
    public function safeUp()
    {
        $this->addColumn('vocabulary','value_ky','varchar(500) NULL');
        $this->addColumn('vocabulary','value_en','varchar(500) NULL');
    }

    public function safeDown()
    {
        echo "m171102_123946_voc_lang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_123946_voc_lang cannot be reverted.\n";

        return false;
    }
    */
}
