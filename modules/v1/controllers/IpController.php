<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\base\BaseObject;
use yii\rest\ActiveController;
use app\modules\v1\models\IpAddresses;

class IpController extends ActiveController
{
    public $modelClass = 'app\modules\v1\models\IpAddresses';

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

    public function actionGetBySubnet()
    {
        $subnet = Yii::$app->request->get('subnet') ?? '';
        $pattern = "/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})-(\d{1,3})+/";

        $ipBegin = preg_replace($pattern, "$1.$2.$3.", $subnet);
        $rangeStart = preg_replace($pattern, "$4", $subnet);
        $rangeEnd = preg_replace($pattern, "$5", $subnet);

        preg_match($pattern, $subnet, $matches);

        if (!empty($matches)) {
            $ipAddresses = IpAddresses::find()
                ->select('id')
                ->where(['like', 'name', $ipBegin])
                ->andFilterWhere(['between', "cast(substring_index(substring_index(name, '.', -1), '/', 1) as unsigned)", $rangeStart, $rangeEnd])
                ->orderBy('id')
                ->all();
        } else {
            $ipAddresses = new IpAddresses();
        }

        return $ipAddresses;
    }
}