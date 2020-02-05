<?php
declare(strict_types=1);

namespace api\controllers\actions\apples\dtos;

use api\models\apple\Apple;
use api\models\apple\AppleColor;
use common\controllers\dtos\BaseDto;

/**
 * Class ApplesDto
 * @package app\modules\api\actions\userApiManager\dtos
 */
class ApplesDto extends BaseDto
{

    /** @var Apple[] */
    public $apples;

    /**
     * @var AppleColor[]
     */
    public $appleColors = [];
}
