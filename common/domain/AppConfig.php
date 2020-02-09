<?php

namespace common\domain;

use \Yii;

class AppConfig
{

    public static function getCorsOrigin(): string
    {
        return Yii::$app->params['corsOrigin'];
    }

    public static function getAppleFreshDuration(): int
    {
        return Yii::$app->params['appleFreshDuration'];
    }

    public static function getAppleEatPrecision(): int
    {
        return Yii::$app->params['appleEatPrecision'];
    }

    public static function getAppleEatPrecisionMax(): int
    {
        return Yii::$app->params['appleEatPrecisionMax'];
    }

    public static function getAppleEatPartPossibility(): float
    {
        return Yii::$app->params['appleEatPartPossibility'];
    }
}
