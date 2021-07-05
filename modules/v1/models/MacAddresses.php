<?php

namespace app\modules\v1\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "mac_addresses".
 *
 * @property int $id
 * @property string $name
 * @property int $ip_address_id
 * @property int $status
 * @property int $attempts
 *
 * @property IpAddresses $ipAddress
 */
class MacAddresses extends ActiveRecord
{
    /** Допустимые значения для генерации */
    const ALLOWABLE_VALUES = '0123456789abcdef';

    /** Максимально допустимое количество попыток для генерации уникального адреса */
    const COUNT_ATTEMPTS = 1000000;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->makeNewMacAddress();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mac_addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ip_address_id'], 'required'],
            [['ip_address_id', 'status', 'attempts'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['ip_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpAddresses::className(), 'targetAttribute' => ['ip_address_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'MAC-адрес',
            'ip_address_id' => 'Ip Address ID',
            'status' => 'Статус активности Mac-адреса',
            'attempts' => 'С какой попытки был создан уникальный Mac-адрес',
        ];
    }

    /**
     * Gets query for [[IpAddress]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIpAddress()
    {
        return $this->hasOne(IpAddresses::className(), ['id' => 'ip_address_id']);
    }

    /** Генерация нового адреса */
    private function makeNewMacAddress()
    {
        $attempts = 0;

        do {
            $fragments = [];

            for ($i = 0; $i < 6; $i++) {
                $fragments[] = substr(str_shuffle(self::ALLOWABLE_VALUES), 0, 2);
            }

            $macAddress = implode('-', $fragments);

            if ($attempts++ > self::COUNT_ATTEMPTS) {
                $macAddress = "";
                break;
            }
        } while (self::findOne(['name' => $macAddress]));

        $this->attempts = $attempts;
        $this->name = $macAddress;
    }
}
