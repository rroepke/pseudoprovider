<?php

use yii\db\Migration;

class m161117_162620_extend_service_table extends Migration
{
    public function up()
    {


        $this->addColumn('service','description',$this->string(2000));
        $this->addColumn('service','return_url',$this->string(2000));
        $this->addColumn('service','timestamp',$this->integer(20));
    }

    public function down()
    {
        echo "m161117_162620_extend_service_table cannot be reverted.\n";

        $this->dropColumn('service','description');
        $this->dropColumn('service','return_url');
        $this->dropColumn('service','timestamp');

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
