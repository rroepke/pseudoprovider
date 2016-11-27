<?php

use yii\db\Migration;
use \yii\db\Schema;

class m161127_111220_add_image_url_to_service extends Migration
{
    public function up()
    {
        $this->addColumn('service', 'image_url', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('service','image_url');

        return false;
    }
}
