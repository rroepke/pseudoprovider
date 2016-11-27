<?php

use yii\db\Migration;

class m161119_102521_extend_service_by_token_field extends Migration
{
    public function up()
    {
        $this->addColumn('service','token',\yii\db\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('service','token');

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
