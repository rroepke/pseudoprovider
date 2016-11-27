<?php

use yii\db\Migration;

class m161122_115455_rename_service_columns extends Migration
{
    public function up()
    {
        $this->renameColumn('service','mode','hash');
        $this->renameColumn('service','cipher','chiffre');
    }

    public function down()
    {
        echo "m161122_115455_rename_service_columns cannot be reverted.\n";

        $this->renameColumn('service','hash','mode');
        $this->renameColumn('service','chiffre','cipher');

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
