<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\base\BaseObject;
use yii\rest\ActiveController;
use app\modules\v1\models\IpAddresses;
use app\modules\v1\models\MacAddresses;

class MacController extends ActiveController
{
    public $modelClass = 'app\modules\v1\models\MacAddresses';

    public function actionGenerate()
    {
        $response = [
            "message" => "Получаем существующую запись",
            "success" => true,
        ];

        // Получаем IP-адрес
        $ip = Yii::$app->request->post('ip') ?? '';
        $ipAddress = IpAddresses::findOne(['name' => $ip]);

        if (!$ipAddress) {
            // Если IP-дрес отсутствует, то добавляем его
            $ipAddress = new IpAddresses();
            $ipAddress->name = $ip;

            if ($ipAddress->validate()) {
                $ipAddress->save();

                $response["message"] = "Запись успешно добавлена";
            } else {
                $response["message"] = "Ошибка! Невалидный ip-адрес";
                $response["success"] = false;
            }
        }

        // Создаем новый MAC-адрес
        $macAddress = new MacAddresses();
        $macAddress->ip_address_id = $ipAddress->id;
        $macAddress->save();

        $response["ipAddress"] = $ipAddress;
        $response["macAddress"] = $macAddress;

        return $response;
    }

    public function actionGetById($id) {
        $response = [
            "message" => "Получаем Mac-адрес по заданному ID",
            "success" => true,
        ];

        $macAddress = MacAddresses::findOne(['id' => $id]);

        if (empty($macAddress)) {
            $response["success"] = false;
            $response["message"] = "Mac-адреса с таким идентификатором не существует";
        }

        $response["macAddress"] = $macAddress;

        return $response;
    }

    public function actionGetByIp($ip) {
        $response = [
            "message" => "Получаем список Mac-адресов по заданному IP",
            "success" => true,
        ];

        // Получаем IP-адрес
        $ipAddress = IpAddresses::findOne(['name' => $ip]);

        if (!$ipAddress) {
            $ipAddress = new IpAddresses();
            $ipAddress->name = $ip;

            $response["success"] = false;
            $response["message"] = $ipAddress->validate() ? "Ошибка! Введенный ip-адрес отсутствует" : "Ошибка! Невалидный IP-адрес";
        } else {
            $macAddresses = $ipAddress->getMacAddresses()->all();

            if (empty($macAddresses)) {
                $response["success"] = false;
                $response["message"] = "Для данного IP список Mac-адресов пуст";
            }

            $response["ipAddress"] = $ipAddress;
            $response["macAddresses"] = $macAddresses;
        }

        return $response;
    }

    public function actionList()
    {
        $response = [
            "message" => "Получаем список Mac-адресов",
            "success" => true,
        ];

        $macAddresses = MacAddresses::find()->all();

        if (empty($macAddresses)) {
            $response["success"] = false;
            $response["message"] = "Список Mac-адресов пуст";
        }

        $response["macAddresses"] = $macAddresses;

        return $response;
    }

    public function actionChangeStatus()
    {
        $response = [
            "message" => "Статус Mac-адреса изменен",
            "success" => true,
        ];

        $id = Yii::$app->request->post('id') ?? 0;
        $macAddress = MacAddresses::findOne(["id" => $id]);

        if (empty($macAddress)) {
            $response["success"] = false;
            $response["message"] = "Mac-адреса с таким идентификатором не существует";
        } else {
            $macAddress->status = (int)!$macAddress->status;
            $macAddress->save();
        }

        $response["macAddress"] = $macAddress;

        return $response;
    }
}
