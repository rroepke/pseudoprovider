<?php

use yii\db\Migration;
use yii\db\Schema;

class m161119_093759_pseudonym_table extends Migration
{
    public function up()
    {
        $this->createTable('pseudonym', [
            'id' => Schema::TYPE_PK,
            'user' => Schema::TYPE_INTEGER . ' NOT NULL',
            'pseudonym' => Schema::TYPE_TEXT,
        ]);
    }

    public function down()
    {
        echo "m161119_093759_pseudonym_table cannot be reverted.\n";

        $this->dropTable('pseudonym');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
