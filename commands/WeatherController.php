<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Weather commands.
 */
class WeatherController extends Controller
{
    /**
     * Weather randomizer.
     * @throws \yii\db\Exception
     * @author Me
     */
    public function actionUpdate()
    {
        $month = date('m');
        $day = date('j');
        for($day; $day != 0; $day--) {
            if($day == 1 && $month != 1) {
                $month--;
                $day = cal_days_in_month(CAL_GREGORIAN, $month, 2);
            }
            echo $day."\n";
            Yii::$app->db->createCommand()->upsert('weather', [
                'temp' => mt_rand(1, 25),
                'date_at' => date('Y-m-d', mktime(0, 0, 0, $month, $day, date('Y')))
            ])->execute();
        }
    }

    /**
     * View data in db.
     *
     * @return int
     * @throws \yii\db\Exception
     */
    public function actionList()
    {
        $data = Yii::$app->db->createCommand("SELECT * FROM weather")->queryAll();
        foreach ($data as $line) {
            echo $line['date_at'].' - '.$line['temp'].'C '.$line['id']."\n";
        }

        return ExitCode::OK;
    }


}
