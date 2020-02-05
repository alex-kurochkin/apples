<?php
declare(strict_types=1);

namespace api\models\apple\services;

use api\models\apple\repositories\AppleColorRepository;
use yii\base\InvalidConfigException;

/**
 * Class AppleService
 * @package api\models\apple\services
 */
class AppleColorService
{

    /**
     * @var AppleColorRepository
     */
    private $appleColorRepository;

    public function __construct(AppleColorRepository $appleColorRepository)
    {
        $this->appleColorRepository = $appleColorRepository;
    }

    /**
     * @param int $userId
     * @return AppleColor[]
     * @throws InvalidConfigException
     */
    public function findManyByUserId(int $userId): array
    {
        return $this->appleColorRepository->findManyByUserId($userId);
    }
}
