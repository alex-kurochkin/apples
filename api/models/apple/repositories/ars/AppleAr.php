<?php
declare(strict_types=1);

namespace api\models\apple\repositories\ars;

use yii\db\ActiveRecord;

/**
 * Class AppleAr
 * @package api\models\repositories\ars
 *
 * @property $id
 * @property $user_id
 * @property $color_id
 * @property $created_at
 * @property $fallen_at
 * @property $eaten_percent
 */
class AppleAr extends ActiveRecord
{

    const ID = 'id';

    const USER_ID = 'user_id';

    const COLOR_ID = 'color_id';

    const MAPPING = [
        'id' => 'id',
        'user_id' => ['userId', 'int'],
        'color_id' => ['colorId', 'int'],
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
