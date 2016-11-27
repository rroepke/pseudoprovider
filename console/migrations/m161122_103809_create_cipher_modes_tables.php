<?php

use yii\db\Migration;
use yii\db\Schema;

class m161122_103809_create_cipher_modes_tables extends Migration
{
    public function up()
    {
        $this->createTable('cipher', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'param' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        $this->createTable('mode', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'param' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m161122_103809_create_cipher_modes_tables cannot be reverted.\n";

        $this->dropTable('mode');

        $this->dropTable('cipher');

        return false;
    }
}
