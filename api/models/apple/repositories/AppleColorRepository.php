<?php
declare(strict_types=1);

namespace api\models\apple\repositories;

use api\models\apple\AppleColor;
use api\models\apple\repositories\ars\AppleColorAr;
use common\domain\persistence\BaseRepository;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class AppleColorRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(AppleColorAr::class, AppleColorAr::MAPPING, AppleColor::class);
    }

    /**
     * @param int $userId
     * @return AppleColor[]
     * @throws InvalidConfigException
     */
    public function findManyByUserId(int $userId): array
    {
        return $this->findMany(function (ActiveQuery $query) use ($userId) {
            $query->where([AppleColorAr::USER_ID => $userId]);
        });
    }
}
