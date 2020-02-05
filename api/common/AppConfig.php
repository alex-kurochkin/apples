<?php
declare(strict_types=1);

namespace api\common;

class AppConfig
{

    public static function getAppleFreshDuration()
    {
        return \Yii::$app->params['appleFreshDuration'];
    }
}
