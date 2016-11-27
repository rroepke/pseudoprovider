<?php

use yii\db\Migration;

class m161122_114806_rename_mode_to_hash extends Migration
{
    public function up()
    {
        $this->renameTable('mode','hash');
        $this->renameTable('cipher','chiffre');
    }

    public function down()
    {
        echo "m161122_114806_rename_mode_to_hash cannot be reverted.\n";

        $this->renameTable('hash','mode');
        $this->renameTable('chiffre','cipher');

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
