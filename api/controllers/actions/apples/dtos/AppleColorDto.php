<?php
declare(strict_types=1);

namespace api\controllers\actions\apples\dtos;

use common\controllers\dtos\BaseDto;

/**
 * Class ApplesDto
 * @package app\modules\api\actions\userApiManager\dtos
 */
class AppleColorDto extends BaseDto
{

    const MAPPING = ['id', 'color'];

    /** @var int */
    public $id;

    /** @var string */
    public $color;
}
