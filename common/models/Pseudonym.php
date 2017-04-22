<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pseudonym".
 *
 * @author Rene Roepke
 *
 * @property integer $id
 * @property integer $user
 * @property string $pseudonym
 */
class Pseudonym extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pseudonym';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user'], 'integer'],
            [['pseudonym'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'pseudonym' => 'Pseudonym',
        ];
    }
}
