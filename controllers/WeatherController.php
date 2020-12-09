<?php

namespace app\controllers;

use Yii;
use georgique\yii2\jsonrpc\exceptions\InternalErrorException;

class WeatherController extends CommonController
{
    /**
     * Get weather by date
     *
     * @param $date string
     */
    public function actionGetByDate($date)
    {
        $temp = Yii::$app->db
            ->createCommand("SELECT temp FROM weather WHERE date_at=:date", ['date' => $date])
            ->queryColumn();
        if($temp) {
            return $temp;
        }

        throw new InternalErrorException('Date not found.');
    }

    /**
     * Get data for last n days.
     *
     * @param $lastDays integer
     */
    public function actionGetHistory($lastDays)
    {
        $date = date('Y-m-d', strtotime("-$lastDays days"));
        $data = Yii::$app->db
            ->createCommand("SELECT date_at, temp FROM weather WHERE date_at >= :date", [
                'date' => $date
            ])->queryAll();
        if($data) {
            return $data;
        }

        throw new InternalErrorException('No result.');
    }

}
