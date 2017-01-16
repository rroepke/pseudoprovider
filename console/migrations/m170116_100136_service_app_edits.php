<?php

use yii\db\Migration;
use \yii\db\Schema;

class m170116_100136_service_app_edits extends Migration
{
    public function up()
    {
        $this->addColumn('service', 'type', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('service','type');

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
