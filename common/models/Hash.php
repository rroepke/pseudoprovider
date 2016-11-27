<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mode".
 *
 * @property integer $id
 * @property string $name
 * @property string $param
 */
class Hash extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hash';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'param'], 'required'],
            [['name', 'param'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'param' => 'Param',
        ];
    }
}
