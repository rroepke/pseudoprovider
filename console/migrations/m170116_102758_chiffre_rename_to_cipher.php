<?php

use yii\db\Migration;

class m170116_102758_chiffre_rename_to_cipher extends Migration
{
    public function up()
    {
        $this->renameTable('chiffre','cipher');

        $this->insert('cipher',array('name'=>'AES-256-CBC','param'=>'AES-256-CBC'));
        $this->insert('cipher',array('name'=>'AES-128-CBC','param'=>'AES-128-CBC'));

        $this->insert('hash',array('name'=>'sha256','param'=>'sha256'));
        $this->insert('hash',array('name'=>'md5','param'=>'md5'));
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
