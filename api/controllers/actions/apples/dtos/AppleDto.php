<?php
declare(strict_types=1);

namespace api\controllers\actions\apples\dtos;

use common\controllers\dtos\BaseDto;
use DateTime;

/**
 * Class ApplesDto
 * @package app\modules\api\actions\userApiManager\dtos
 */
class AppleDto extends BaseDto
{

    const MAPPING = [
        'id',
        'colorId',
        'createdAt' => ['createdAt', 'datetime'],
        'fallenAt' => ['fallenAt', 'datetime'],
        'eatenPercent',
    ];

    /** @var int */
    public $id;

    /** @var int */
    public $colorId;

    /**
     * @var DateTime
     */
    public $createdAt;

    /**
     * @var DateTime
     */
    public $fallenAt;

    /**
     * @var float
     */
    public $eatenPercent;
}
