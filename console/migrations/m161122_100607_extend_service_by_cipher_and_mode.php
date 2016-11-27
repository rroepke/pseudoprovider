<?php

use yii\db\Migration;

class m161122_100607_extend_service_by_cipher_and_mode extends Migration
{
    public function up()
    {
        $this->addColumn('service','cipher',\yii\db\Schema::TYPE_STRING);
        $this->addColumn('service','mode',\yii\db\Schema::TYPE_STRING);
    }

    public function down()
    {
        echo "m161122_100607_extend_service_by_cipher_and_mode cannot be reverted.\n";

        $this->dropColumn('service','cipher');
        $this->dropColumn('service','mode');

        return false;
    }
}
