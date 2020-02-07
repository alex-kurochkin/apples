<?php
declare(strict_types=1);

namespace api\models\apple\services;

use api\models\apple\Apple;
use api\models\apple\repositories\AppleRepository;
use common\domain\persistence\exceptions\EntityNotFoundException;
use common\domain\utils\ObjectArrays;
use yii\base\InvalidConfigException;

/**
 * Class AppleService
 * @package api\models\apple\services
 */
class AppleService
{

    /**
     * @var AppleRepository
     */
    private $appleRepository;

    /**
     * @var AppleColorService
     */
    private $appleColorService;

    public function __construct(AppleRepository $appleRepository, AppleColorService $appleColorService)
    {
        $this->appleRepository = $appleRepository;
        $this->appleColorService = $appleColorService;
    }

    /**
     * @param int $userId
     * @return Apple[]
     * @throws InvalidConfigException
     */
    public function findManyByUserId(int $userId): array
    {
        return $this->appleRepository->findManyByUserId($userId);
    }

    /**
     * @param int $appleId
     * @param int $userId
     * @return Apple
     * @throws InvalidConfigException
     */
    public function findOneByIdAndUserId(int $appleId, int $userId): Apple
    {
        return $this->appleRepository->findOneByIdAndUserId($appleId, $userId);
    }

    /**
     * @param int $userId
     * @param int $count
     * @throws InvalidConfigException
     */
    public function createMany(int $userId, int $count)
    {
        $now = time();
        $apples = [];

        $appleColors = $this->appleColorService->findManyByUserId($userId);
        $colorIds = ObjectArrays::createFieldArray($appleColors, 'id');

        while ($count--) {
            $apple = new Apple;
            $apple->userId = $userId;
            $apple->colorId = $colorIds[array_rand($colorIds)];
            $apple->createdAt = date_create_from_format('U', (string)rand($now - 10 * 86400, $now));
            $apples[] = $apple;
        }

        $this->appleRepository->createMany($apples);
    }

    /**
     * @param Apple $apple
     * @return Apple
     */
    public function updateOne(Apple $apple): Apple
    {
        return $this->appleRepository->updateOne($apple);
    }

    public function deleteOne($userId, $appleId)
    {
        $model = $this->appleRepository->findOneByIdAndUserId($appleId, $userId);
        $this->appleRepository->deleteOne($model);
    }
}
