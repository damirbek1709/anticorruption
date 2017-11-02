<?php

use yii\db\Migration;

class m171102_125412_doc_langs extends Migration
{
    public function safeUp()
    {
        $this->addColumn('document','title_ky','varchar(255) NULL');
        $this->addColumn('document','text_ky','text');
        $this->addColumn('document','title_en','varchar(255) NULL');
        $this->addColumn('document','text_en','text');
    }

    public function safeDown()
    {
        echo "m171102_125412_doc_langs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171102_125412_doc_langs cannot be reverted.\n";

        return false;
    }
    */
}
