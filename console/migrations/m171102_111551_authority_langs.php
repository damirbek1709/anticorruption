<?php

use yii\db\Migration;

class m171102_111551_authority_langs extends Migration
{
    public function safeUp()
    {
        $this->addColumn('authority','title_ky','varchar(255) NULL');
        $this->addColumn('authority','text_ky','text');
        $this->addColumn('authority','title_en','varchar(255) NULL');
        $this->addColumn('authority','text_en','text');
    }

    public function safeDown()
    {
        echo "m171102_111551_authority_langs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_111551_authority_langs cannot be reverted.\n";

        return false;
    }
    */
}
