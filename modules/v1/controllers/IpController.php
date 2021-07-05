<?php

namespace app\modules\api\controllers;

use Yii;
use yii\base\BaseObject;
use yii\rest\ActiveController;
use app\modules\api\models\IpAddresses;

class IpController extends ActiveController
{
    public $modelClass = 'app\modules\api\models\IpAddresses';

    public function actionGetById($id) {
        $response = [
            "message" => "Получаем IP-адрес по заданному ID",
            "success" => true,
        ];

        $ipAddress = IpAddresses::findOne(['id' => $id]);

        if (empty($ipAddress)) {
            $response["success"] = false;
            $response["message"] = "IP-адреса с таким идентификатором не существует";
        }

        $response["macAddress"] = $ipAddress;

        return $response;
    }

    public function actionList()
    {
        $response = [
            "message" => "Получаем список IP-адресов",
            "success" => true,
        ];

        $ipAddresses = IpAddresses::find()->orderBy('id')->all();

        if (empty($ipAddresses)) {
            $response["success"] = false;
            $response["message"] = "Список IP-адресов пуст";
        }

        $response["ipAddresses"] = $ipAddresses;

        return $response;
    }
}