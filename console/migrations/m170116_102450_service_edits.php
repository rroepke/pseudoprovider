<?php

use yii\db\Migration;

class m170116_102450_service_edits extends Migration
{
    public function up()
    {
        $this->renameColumn('service','chiffre','cipher');
    }

    public function down()
    {
        $this->renameColumn('service','cipher','chiffre');

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
