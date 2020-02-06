<?php

namespace common\domain;

use \Yii;

class AppConfig
{

    public static function getCorsOrigin()
    {
        return Yii::$app->params['CorsOrigin'];
    }

    public static function getAppleFreshDuration()
    {
        return Yii::$app->params['appleFreshDuration'];
    }
}
