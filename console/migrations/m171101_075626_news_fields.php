<?php

use yii\db\Migration;

class m171101_075626_news_fields extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m171101_075626_news_fields cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('news', 'title_ky', 'VARCHAR(255)');
        $this->addColumn('news', 'title_en', 'VARCHAR(255)');

        $this->addColumn('news', 'description_ky', 'VARCHAR(255)');
        $this->addColumn('news', 'description_en', 'VARCHAR(255)');

        $this->addColumn('news', 'text_ky', 'TEXT');
        $this->addColumn('news', 'text_en', 'TEXT');
    }

    /*
    public function down()
    {
        echo "m171101_075626_news_fields cannot be reverted.\n";

        return false;
    }
    */
}
