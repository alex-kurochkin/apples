<?php
declare(strict_types=1);

namespace api\models\apple\repositories;

use api\models\apple\Apple;
use api\models\apple\repositories\ars\AppleAr;
use common\domain\persistence\BaseRepository;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class AppleRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(AppleAr::class, AppleAr::MAPPING, Apple::class);
    }

    /**
     * @param int $userId
     * @return Apple[]
     * @throws InvalidConfigException
     */
    public function findManyByUserId(int $userId): array
    {
        return $this->findMany(function (ActiveQuery $query) use ($userId) {
            $query->where([AppleAr::USER_ID => $userId]);
        });
    }

    /**
     * @param Apple[] $apples
     */
    public function createMany(array $apples) {
        foreach ($apples as $apple) {
            $this->createOne($apple);
        }
    }
}
