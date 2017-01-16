<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $created_by
 * @property string $description
 * @property string $image_url
 * @property string $return_url
 * @property integer $timestamp
 * @property string $token
 * @property string $cipher
 * @property string $hash
 */
class Service extends \yii\db\ActiveRecord
{
    const SCENARIO_WEB = 'web';
    const SCENARIO_APP = 'app';

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_WEB] = ['name', 'type', 'url', 'description', 'return_url', 'image_url', 'created_by', 'timestamp', 'cipher', 'hash'];
        $scenarios[self::SCENARIO_APP] = ['name', 'type', 'description', 'created_by', 'timestamp', 'cipher', 'hash'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cipher', 'hash'], 'required'],
            [['return_url', 'url'], 'required', 'on' => self::SCENARIO_WEB],
            [['created_by', 'timestamp'], 'integer'],
            [['name', 'cipher', 'hash'], 'string', 'max' => 255],
            [['url', 'description', 'return_url', 'image_url'], 'string', 'max' => 2000],
            ['name', 'unique']
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
            'url' => 'URL',
            'created_by' => 'Created By',
            'description' => 'Description',
            'return_url' => 'Return URL',
            'timestamp' => 'Registered since',
            'token' => 'Secret Key',
            'cipher' => 'Cipher',
            'hash' => 'Hash',
            'image_url' => 'Image URL',
        ];
    }
}

