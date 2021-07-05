<?php

namespace app\modules\v1\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "ip_addresses".
 *
 * @property int $id
 * @property string $name
 *
 * @property MacAddresses[] $macAddresses
 */
class IpAddresses extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ip_addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'IP-адрес',
        ];
    }

    /**
     * Gets query for [[MacAddresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMacAddresses()
    {
        return $this->hasMany(MacAddresses::className(), ['ip_address_id' => 'id']);
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $patternFragment = '((25[0-5])|(2[0-4]\d)|(1\d{2})|(\d{1,2}))';
        $patternIp = "/($patternFragment\.){3}$patternFragment/";
        preg_match($patternIp, $this->name, $matches);

        return strcmp($this->name, $matches[0]) == 0;
    }
}
