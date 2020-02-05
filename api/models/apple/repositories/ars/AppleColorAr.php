<?php
declare(strict_types=1);

namespace api\models\apple\repositories\ars;

use yii\db\ActiveRecord;

/**
 * Class AppleColorAr
 * @package api\models\repositories\ars
 *
 * @property $id
 * @property $user_id
 * @property $color
 */
class AppleColorAr extends ActiveRecord
{

    const ID = 'id';

    const USER_ID = 'user_id';

    const COLOR = 'color';

    const MAPPING = [
        'id' => 'id',
        'user_id' => ['userId', 'int'],
        'color' => 'color',
    ];

    /** @inheritdoc */
    public static function tableName(): string
    {
        return 'apple_color';
    }
}
