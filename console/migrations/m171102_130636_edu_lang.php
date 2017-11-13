<?php

use yii\db\Migration;

class m171102_130636_edu_lang extends Migration
{
    public function safeUp()
    {
        $this->addColumn('education','title_ky','varchar(255) NULL');
        $this->addColumn('education','text_ky','text');
        $this->addColumn('education','title_en','varchar(255) NULL');
        $this->addColumn('education','text_en','text');
    }

    public function safeDown()
    {
        echo "m171102_130636_edu_lang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_130636_edu_lang cannot be reverted.\n";

        return false;
    }
    */
}
