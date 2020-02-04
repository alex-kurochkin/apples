<?php

namespace common\fixtures\apples;

use api\models\apple\repositories\ars\AppleColorAr;
use yii\test\ActiveFixture;

class AppleColorArFixture extends ActiveFixture
{
    public $modelClass = AppleColorAr::class;
    public $dataFile = __DIR__ . '/data/AppleColorArs.php';
}
