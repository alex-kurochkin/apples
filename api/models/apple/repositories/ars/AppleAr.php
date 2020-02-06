<?php
declare(strict_types=1);

namespace api\models\apple\repositories\ars;

use common\domain\persistence\BaseAr;

/**
 * Class AppleAr
 * @package api\models\repositories\ars
 *
 * @property int $id
 * @property int $user_id
 * @property int $color_id
 * @property string $created_at (DateTime)
 * @property string $fallen_at (DateTime)
 * @property float $eaten_percent
 */
class AppleAr extends BaseAr
{

    const ID = 'id';

    const USER_ID = 'user_id';

    const COLOR_ID = 'color_id';

    const MAPPING = [
        self::ID => ['id', 'int'],
        self::USER_ID => ['userId', 'int'],
        self::COLOR_ID => ['colorId', 'int'],
        'eaten_percent' => 'eatenPercent',
        'created_at' => ['createdAt', 'datetime', false],
        'fallen_at' => ['fallenAt', 'datetime', false],
    ];

    /** @inheritdoc */
    public static function tableName(): string
    {
        return 'apple';
    }
}
