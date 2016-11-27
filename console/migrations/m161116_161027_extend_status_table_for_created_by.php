<?php

use yii\db\Migration;
use yii\db\Schema;

class m161116_161027_extend_status_table_for_created_by extends Migration
{
    public function up()
    {
        $this->createTable('service', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'url' => $this->string(2000),
        ]);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn('{{%service}}','created_by',Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
        $this->addForeignKey('fk_status_created_by', '{{%service}}', 'created_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_status_created_by','{{%service}}');
        $this->dropColumn('{{%service}}','created_by');
    }
}
