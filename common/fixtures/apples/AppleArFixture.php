<?php

namespace common\fixtures\apples;

use api\models\apple\repositories\ars\AppleAr;
use yii\test\ActiveFixture;

class AppleArFixture extends ActiveFixture
{
    public $modelClass = AppleAr::class;
    public $dataFile = __DIR__ . '/data/AppleArs.php';
}
