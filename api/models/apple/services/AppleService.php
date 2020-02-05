<?php
declare(strict_types=1);

namespace api\models\apple\services;

use api\models\apple\Apple;
use api\models\apple\repositories\AppleRepository;
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

    public function __construct(AppleRepository $appleRepository)
    {
        $this->appleRepository = $appleRepository;
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
}
