<?php
declare(strict_types=1);

namespace api\controllers\actions\apples\params;

use common\controllers\params\Params;
use common\domain\AppConfig;

class EatParams extends Params
{

    /**
     * @var float
     */
    public $eatenPercent;

    /**
     * @var int
     */
    public $eatPercentPrecision;

    public function rules(): array
    {
        return [
            ['eatenPercent', 'double', 'min' => 0, 'max' => 1],
            ['eatPercentPrecision', 'double', 'min' => 0, 'max' => AppConfig::getAppleEatPrecisionMax()],
            [['eatenPercent', 'eatPercentPrecision'], 'required'],
        ];
    }
}
