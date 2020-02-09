<?php
declare(strict_types=1);

namespace api\controllers\actions\apples;

use common\components\Controller;
use common\domain\AppConfig;
use common\domain\AppContext;
use api\controllers\actions\apples\params\EatParams;
use api\models\apple\services\AppleService;
use api\models\apple\utils\Apples;
use common\controllers\dtos\ObjectResponseDto;
use common\domain\utils\ErrorMessageBuilder;
use Exception;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;

class EatAction extends Action
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
     * GetApiSettingsAction constructor.
     * @param $id
     * @param Controller $controller
     * @param AppContext $context
     * @param AppleService $appleService
     * @param array $config
     */
    public function __construct(
        $id,
        Controller $controller,
        AppContext $context,
        AppleService $appleService,
        $config = []
    ) {
        $this->appContext = $context;
        $this->appleService = $appleService;

        parent::__construct($id, $controller, $config);
    }

    /**
     * @param int $id
     * @return ObjectResponseDto
     * @throws BadRequestHttpException
     * @throws InvalidConfigException
     */
    public function run(int $id)
    {
        $patch = $this->controller->getRequest()->getBodyParams();

        $params = new EatParams();
        $params->load($patch);
        if (!$params->validate()) {
            throw new BadRequestHttpException(ErrorMessageBuilder::build($params->errors));
        }

        $percent = $params->eatenPercent;
        $eatPercentPrecision = $params->eatPercentPrecision;

        $userId = $this->appContext->getUserId();

        try {
            $apple = $this->appleService->findOneByIdAndUserId($id, $userId);

            $percent = round($percent, $eatPercentPrecision);
            Apples::checkEatPossibility($apple, $percent);

            $apple->eatenPercent = round($apple->eatenPercent + $percent, AppConfig::getAppleEatPrecision());

            $this->appleService->updateOne($apple);

            $paramsDto = new EatParams();
            $paramsDto->eatenPercent = $apple->eatenPercent;
            $paramsDto->eatPercentPrecision = AppConfig::getAppleEatPrecision();
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new ObjectResponseDto($paramsDto);
    }
}
