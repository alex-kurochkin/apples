<?php
declare(strict_types=1);

namespace api\controllers\actions\apples;

use api\common\AppConfig;
use api\common\AppContext;
use api\controllers\actions\apples\dtos\AppleColorDto;
use api\controllers\actions\apples\dtos\AppleDto;
use api\controllers\actions\apples\dtos\ApplesDto;
use api\models\apple\Apple;
use api\models\apple\AppleColor;
use api\models\apple\services\AppleColorService;
use api\models\apple\services\AppleService;
use common\controllers\dtos\ObjectResponseDto;
use common\domain\mappers\dto\DtoMapper;
use yii\base\Action;
use yii\base\Controller;
use yii\base\InvalidConfigException;

class ListAction extends Action
{

    /**
     * @var AppContext
     */
    private $appContext;

    /**
     * @var AppleService
     */
    private $appleService;

    /**
     * @var AppleColorService
     */
    private $appleColorService;

    /**
     * @var DtoMapper
     */
    private $appleMapper;

    /**
     * @var DtoMapper
     */
    private $appleColorMapper;

    /**
     * GetApiSettingsAction constructor.
     * @param $id
     * @param Controller $controller
     * @param AppContext $context
     * @param AppleService $appleService
     * @param AppleColorService $appleColorService
     * @param array $config
     */
    public function __construct(
        $id,
        Controller $controller,
        AppContext $context,
        AppleService $appleService,
        AppleColorService $appleColorService,
        $config = []
    ) {
        $this->appContext = $context;
        $this->appleService = $appleService;
        $this->appleColorService = $appleColorService;

        $this->appleMapper = new DtoMapper(AppleDto::class, AppleDto::MAPPING, Apple::class);
        $this->appleColorMapper = new DtoMapper(AppleColorDto::class, AppleColorDto::MAPPING, AppleColor::class);

        parent::__construct($id, $controller, $config);
    }

    /**
     * @return ObjectResponseDto
     * @throws InvalidConfigException
     */
    public function run()
    {
        $applesDto = new ApplesDto();

        $userId = $this->appContext->getUserId();
        $apples = $this->appleService->findManyByUserId($userId);
        $appleColors = $this->appleColorService->findManyByUserId($userId);

        if ($apples) {
            $applesDto->apples = $this->appleMapper->toManyDtos($apples);
        }

        if ($appleColors) {
            $applesDto->appleColors = $this->appleColorMapper->toManyDtos($appleColors);
        }

        $applesDto->freshDuration = AppConfig::getAppleFreshDuration(); // hours

        return new ObjectResponseDto($applesDto);
    }
}
