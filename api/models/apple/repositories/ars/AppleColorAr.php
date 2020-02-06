<?php
declare(strict_types=1);

namespace api\models\apple\repositories\ars;

use common\domain\persistence\BaseAr;

/**
 * Class AppleColorAr
 * @package api\models\repositories\ars
 *
 * @property int $id
 * @property int $user_id
 * @property string $color
 */
class AppleColorAr extends BaseAr
{

    const ID = 'id';

    const USER_ID = 'user_id';

    const COLOR = 'color';

    const MAPPING = [
        self::ID => 'id',
        self::USER_ID => ['userId', 'int'],
        self::COLOR => 'color',
    ];

    /** @inheritdoc */
    public static function tableName(): string
    {
        return 'apple_color';
    }
}
