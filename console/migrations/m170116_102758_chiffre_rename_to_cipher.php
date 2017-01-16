<?php

use yii\db\Migration;

class m170116_102758_chiffre_rename_to_cipher extends Migration
{
    public function up()
    {
        $this->renameTable('chiffre','cipher');
    }

    public function down()
    {
        $this->renameTable('cipher','chiffre');

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
