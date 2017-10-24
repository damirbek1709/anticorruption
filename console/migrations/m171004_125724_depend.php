<?php

use yii\db\Migration;

class m171004_125724_depend extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('depend', [
            'id' => $this->primaryKey(),
            'table_name' => $this->string('20')->notNull(),
            'last_update' => $this->string('20')->notNull(),
        ],$tableOptions);

        $this->insert('depend',array(
            'table_name'=>'vocabulary',
            'last_update' =>time(),
        ));
    }

    public function safeDown()
    {
        echo "m171004_125724_depend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171004_125724_depend cannot be reverted.\n";

        return false;
    }
    */
}
