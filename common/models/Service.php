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
 * @property string $chiffre
 * @property string $hash
 */
class Service extends \yii\db\ActiveRecord
{
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
            [['name', 'url', 'return_url', 'chiffre', 'hash'], 'required'],
            [['created_by', 'timestamp'], 'integer'],
            [['name', 'chiffre', 'hash'], 'string', 'max' => 255],
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
            'token' => 'Token',
            'chiffre' => 'Chiffre',
            'hash' => 'Hash',
            'image_url' => 'Image URL',
        ];
    }
}

